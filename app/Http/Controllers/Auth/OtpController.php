<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Mail\MagicLinkMail;
use App\Models\DeletionRequest;
use App\Models\User;
use App\Services\Otp\OtpService;
use App\Support\LoginGate;
use App\Support\Phone;
use App\Support\TrustedDevice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/** Codes à usage unique : vérification du compte, connexion sans mot de passe, réinitialisation, suppression. */
class OtpController extends Controller
{
    public function __construct(private readonly OtpService $otp) {}

    public function show(Request $request): View|RedirectResponse
    {
        $ctx = $request->session()->get('otp');

        if (! $ctx) {
            return redirect()->route('login');
        }

        return view('auth.otp', [
            'channel' => $this->otp->channelFor($ctx['identifier']),
            'masque' => $this->masked($ctx['identifier']),
            'back' => $ctx['back'] ?? route('login'),
            'restants' => session('restants'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $ctx = $request->session()->get('otp');
        abort_unless($ctx, 403);

        $request->validate(['code' => ['required', 'digits:6']], [
            'code.required' => 'Saisissez le code à 6 chiffres.',
            'code.digits' => 'Le code comporte 6 chiffres.',
        ]);

        $record = $this->otp->verify($ctx['identifier'], $ctx['purpose'], $request->string('code'), $restants);

        if ($record === null) {
            return back()->with('restants', $restants)->withErrors(['code' => 'invalid']);
        }

        $request->session()->forget('otp');

        return match ($ctx['purpose']) {
            OtpService::VERIFY => $this->afterVerify($request, $record->user_id, $ctx['identifier']),
            OtpService::LOGIN => $this->afterLogin($request, $ctx['identifier']),
            OtpService::RESET => $this->afterReset($request, $ctx['identifier']),
            OtpService::DELETE => $this->afterDelete($request),
        };
    }

    public function resend(Request $request): RedirectResponse
    {
        $ctx = $request->session()->get('otp');
        abort_unless($ctx, 403);

        if ($this->otp->sentRecently($ctx['identifier'], $ctx['purpose'])) {
            return back()->with('renvoi', 'Un code vient d\'être envoyé. Patientez une minute avant d\'en demander un autre.');
        }

        $this->otp->issue($ctx['identifier'], $ctx['purpose'], $this->userFor($ctx));

        return back()->with('renvoi', 'Un nouveau code vient de vous être envoyé.');
    }

    /** « Recevoir un code de connexion » : SMS pour un téléphone, lien par e-mail pour une adresse. */
    public function requestLoginCode(Request $request): RedirectResponse
    {
        $request->validate(['identifier' => ['required', 'string', 'max:255']], [
            'identifier.required' => 'Indiquez votre téléphone ou votre e-mail.',
        ]);

        $identifier = $this->otp->canonical((string) $request->input('identifier'));

        if ($identifier === null) {
            return back()->withInput()->withErrors(['identifier' => 'Ce téléphone ou cet e-mail ne semble pas valide.']);
        }

        if ($this->otp->channelFor($identifier) === 'sms' && ! $this->otp->smsEnabled()) {
            return back()->withInput()->withErrors(['identifier' => 'Les codes par SMS ne sont pas encore disponibles. Utilisez votre adresse e-mail.']);
        }

        // Réponse identique que le compte existe ou non (pas d'énumération des comptes).
        $user = User::findByIdentifier($identifier);

        if ($user && $user->status === UserStatus::Inactive) {
            LoginGate::allow($user);
        }

        if ($this->otp->channelFor($identifier) === 'email') {
            if ($user && $user->status !== UserStatus::Suspended) {
                $this->sendMagicLink($user);
            }

            return redirect()->route('login.magic.sent')->with('magic_email', $identifier);
        }

        if ($user && $user->status !== UserStatus::Suspended && ! $this->otp->sentRecently($identifier, OtpService::LOGIN)) {
            $this->otp->issue($identifier, OtpService::LOGIN, $user);
        }

        $request->session()->put('otp', [
            'identifier' => $identifier, 'purpose' => OtpService::LOGIN, 'back' => route('login'),
        ]);

        return redirect()->route('otp.show');
    }

    public function magicSent(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('magic_email');

        if (! $email) {
            return redirect()->route('login');
        }

        $request->session()->keep('magic_email');

        return view('auth.lien-magique', ['email' => $email, 'masque' => $this->masked($email)]);
    }

    public function resendMagic(Request $request): RedirectResponse
    {
        $email = $request->session()->get('magic_email');
        abort_unless($email, 403);

        $user = User::findByIdentifier($email);

        if ($user && $user->status !== UserStatus::Suspended && ! Cache::add("magic-resend.{$user->id}", 1, config('otp.resend_after'))) {
            return back()->with('magic_email', $email)->with('renvoi', 'Un lien vient d\'être envoyé. Patientez une minute.');
        }

        if ($user && $user->status !== UserStatus::Suspended) {
            $this->sendMagicLink($user);
        }

        return back()->with('magic_email', $email)->with('renvoi', 'Un nouveau lien vient de vous être envoyé.');
    }

    /** Lien signé, valable quelques minutes, à usage unique. */
    public function magicLogin(Request $request, User $user, string $token): RedirectResponse
    {
        abort_unless(Cache::pull("magic.{$user->id}.{$token}"), 403, 'Ce lien n\'est plus valable. Demandez-en un nouveau.');

        $user = LoginGate::allowPending($user);

        if ($user->email_verified_at === null) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return $this->finishLogin($request, $user);
    }

    private function sendMagicLink(User $user): void
    {
        $token = Str::random(40);
        $ttl = (int) config('otp.magic_link_ttl');

        Cache::put("magic.{$user->id}.{$token}", true, now()->addMinutes($ttl));

        $url = URL::temporarySignedRoute('login.magic', now()->addMinutes($ttl), ['user' => $user->id, 'token' => $token]);

        Mail::to($user->email)->send(new MagicLinkMail($url, $ttl));
    }

    private function afterVerify(Request $request, ?int $userId, string $identifier): RedirectResponse
    {
        $user = User::findOrFail($userId);

        $user->forceFill([
            'status' => UserStatus::Active,
            $this->otp->channelFor($identifier) === 'sms' ? 'phone_verified_at' : 'email_verified_at' => now(),
        ])->save();

        return $this->finishLogin($request, $user);
    }

    private function afterLogin(Request $request, string $identifier): RedirectResponse
    {
        $user = User::findByIdentifier($identifier);

        if (! $user) {
            return redirect()->route('login');
        }

        $user = LoginGate::allowPending($user);

        $column = $this->otp->channelFor($identifier) === 'sms' ? 'phone_verified_at' : 'email_verified_at';
        $user->forceFill([$column => $user->{$column} ?? now()])->save();

        return $this->finishLogin($request, $user);
    }

    private function afterReset(Request $request, string $identifier): RedirectResponse
    {
        $user = User::findByIdentifier($identifier);

        if (! $user) {
            return redirect()->route('login');
        }

        $request->session()->put('reset_user_id', $user->id);

        return redirect()->route('password.new');
    }

    private function afterDelete(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user, 403);

        DeletionRequest::firstOrCreate(['user_id' => $user->id, 'status' => 'pending']);

        return redirect()->route('espace.profil')->with('status', 'Votre demande de suppression est enregistrée. Un membre du bureau la traite sous peu.');
    }

    /** Connexion finale ; passe par le défi 2FA si le compte l'a activée. */
    public function finishLogin(Request $request, User $user): RedirectResponse
    {
        if ($user->status === UserStatus::PendingVerification) {
            $user->forceFill(['status' => UserStatus::Active])->save();
        }

        if ($user->hasConfirmedTwoFactor() && ! TrustedDevice::valid($request, $user)) {
            $request->session()->put(['login.id' => $user->getKey(), 'login.remember' => false]);

            return redirect()->route('two-factor.login');
        }

        Auth::login($user);
        $request->session()->regenerate();
        $user->forceFill(['last_activity_at' => now()])->save();

        return redirect()->intended(config('fortify.home'));
    }

    private function userFor(array $ctx): ?User
    {
        return in_array($ctx['purpose'], [OtpService::LOGIN, OtpService::RESET, OtpService::VERIFY], true)
            ? User::findByIdentifier($ctx['identifier'])
            : Auth::user();
    }

    private function masked(string $identifier): string
    {
        if (Phone::isIdentifierEmail($identifier)) {
            [$local, $domain] = explode('@', $identifier, 2);

            return Str::substr($local, 0, 1).'•••@'.$domain;
        }

        return Phone::mask($identifier);
    }
}
