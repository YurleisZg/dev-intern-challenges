<?php

namespace App\Features\Yurleis\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController
{
    public function __invoke(Request $request)
    {
        Auth::guard('yurleis')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('yurleis.challenges.auth.login');
    }
}
