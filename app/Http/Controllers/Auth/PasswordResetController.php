<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\ResetUserPassword;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordResetController extends Controller
{
    public function __construct(private readonly OtpService $otp) {}

    public function request(): View
    {
        return view('auth.mdp-oublie');
    }

    public function send(Request $request): RedirectResponse
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

        // Même réponse que le compte existe ou non.
        $user = User::findByIdentifier($identifier);

        if ($user && $user->status !== UserStatus::Suspended && ! $this->otp->sentRecently($identifier, OtpService::RESET)) {
            $this->otp->issue($identifier, OtpService::RESET, $user);
        }

        $request->session()->put('otp', [
            'identifier' => $identifier, 'purpose' => OtpService::RESET, 'back' => route('password.request'),
        ]);

        return redirect()->route('otp.show');
    }

    public function form(Request $request): View|RedirectResponse
    {
        return $request->session()->has('reset_user_id')
            ? view('auth.nouveau-mdp')
            : redirect()->route('password.request');
    }

    public function update(Request $request, ResetUserPassword $reset): RedirectResponse
    {
        $user = User::find($request->session()->get('reset_user_id'));

        if (! $user) {
            return redirect()->route('password.request');
        }

        $reset->reset($user, $request->only('password', 'password_confirmation'));

        $user->forceFill(['password_changed_at' => now()])->save();

        // Par sécurité : déconnexion de tous les appareils, puis connexion de celui-ci.
        $user->logoutEverywhereElse();
        $request->session()->forget('reset_user_id');

        if ($user->hasConfirmedTwoFactor()) {
            $request->session()->put(['login.id' => $user->getKey(), 'login.remember' => false]);

            return redirect()->route('two-factor.login');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(config('fortify.home'));
    }
}
