<?php

namespace App\Features\Elkin\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController
{
    public function __invoke(Request $request)
    {
        Auth::guard('elkin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('elkin.challenges.auth.login');
    }
}
