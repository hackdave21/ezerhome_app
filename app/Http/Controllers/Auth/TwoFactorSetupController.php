<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Fortify;

class TwoFactorSetupController extends Controller
{
    /** Étape 1-3 : application, QR code, code de confirmation. */
    public function show(Request $request, EnableTwoFactorAuthentication $enable): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasConfirmedTwoFactor()) {
            return redirect()->route('espace.profil');
        }

        // Génère (ou réutilise) le secret, tant que la 2FA n'est pas confirmée.
        if ($user->two_factor_secret === null) {
            $enable($user);
            $user->refresh();
        }

        $secret = Fortify::currentEncrypter()->decrypt($user->two_factor_secret);

        return view('auth.2fa-activation', [
            'qrSvg' => $user->twoFactorQrCodeSvg(),
            'cle' => trim(chunk_split($secret, 4, ' ')),
        ]);
    }

    public function confirm(Request $request, ConfirmTwoFactorAuthentication $confirm): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']], [
            'code.required' => 'Saisissez le code à 6 chiffres affiché dans l\'application.',
            'code.digits' => 'Le code comporte 6 chiffres.',
        ]);

        $user = $request->user();

        try {
            $confirm($user, $request->string('code'));
        } catch (ValidationException) {
            return back()->withErrors(['code' => 'Ce code ne correspond pas. Vérifiez-le et réessayez.']);
        }

        // Les codes de secours ne sont affichés qu'une seule fois.
        $request->session()->flash('recovery_codes', $user->fresh()->recoveryCodes());

        return redirect()->route('two-factor.codes');
    }

    public function codes(Request $request): View|RedirectResponse
    {
        $codes = $request->session()->get('recovery_codes');

        if (! $codes) {
            return redirect()->route('espace.profil');
        }

        return view('auth.2fa-secours', ['codes' => $codes]);
    }

    /** Nouveaux codes de secours (les anciens cessent de fonctionner), affichés une seule fois. */
    public function regenerate(Request $request, GenerateNewRecoveryCodes $generate): RedirectResponse
    {
        abort_unless($request->user()->hasConfirmedTwoFactor(), 403);

        $generate($request->user());
        $request->session()->flash('recovery_codes', $request->user()->fresh()->recoveryCodes());

        return redirect()->route('two-factor.codes');
    }

    public function disable(Request $request, DisableTwoFactorAuthentication $disable): RedirectResponse
    {
        abort_if($request->user()->mustUseTwoFactor(), 403, 'La double authentification est obligatoire pour votre rôle.');

        $disable($request->user());

        return redirect()->route('espace.profil')->with('status', 'La double authentification est désactivée.');
    }
}
