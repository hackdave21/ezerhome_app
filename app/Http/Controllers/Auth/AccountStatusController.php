<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountStatusController extends Controller
{
    public function deactivated(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get('deactivated_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $request->session()->keep('deactivated_user_id');

        return view('auth.compte-desactive', ['user' => User::find($userId)]);
    }
}
