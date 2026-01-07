<?php

namespace App\Features\Yurleis\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function show()
    {
        return view('yurleis-auth::login'); 
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::guard('yurleis')->attempt($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Credenciales inválidas.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('yurleis.dashboardYurleis'));
    }
}
