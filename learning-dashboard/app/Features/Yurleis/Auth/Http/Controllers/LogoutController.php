<?php

namespace App\Features\Yurleis\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController
{
    public function __invoke(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // vuelve al login del feature
        return redirect()->route('yurleis.challenges.auth.login');
    }
}
