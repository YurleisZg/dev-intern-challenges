<?php

namespace App\Features\Elkin\Auth\Http\Controllers;

use App\Models\Elkin\ElkinUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class RegisterController
{
    public function show()
    {
        return view('auth::register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('elkin_users','email')],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = ElkinUser::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::guard('elkin')->login($user);
        $request->session()->regenerate();

        return redirect()->route('elkin.dashboard');
    }
}
