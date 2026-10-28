<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Services\Otp\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('auth.inscription');
    }

    public function store(Request $request, CreateNewUser $creator, OtpService $otp): RedirectResponse
    {
        $user = $creator->create($request->all());

        event(new Registered($user));

        // Le code part vers le téléphone si la passerelle SMS existe, sinon vers l'e-mail.
        $identifier = ($user->phone && $otp->smsEnabled()) ? $user->phone : $user->email;

        $otp->issue($identifier, OtpService::VERIFY, $user);

        $request->session()->put('otp', [
            'identifier' => $identifier,
            'purpose' => OtpService::VERIFY,
            'passwordless' => $user->password === null,
            'back' => route('register'),
        ]);

        return redirect()->route('otp.show');
    }
}
