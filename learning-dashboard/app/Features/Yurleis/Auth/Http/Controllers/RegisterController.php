<?php

namespace App\Features\Yurleis\Auth\Http\Controllers;

use App\Models\Yurleis\User as YurleisUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController
{
    public function show()
    {
        return view('yurleis-auth::register'); 
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('yurleis_users','email')],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = YurleisUser::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']), 
        ]);

        Auth::guard('yurleis')->login($user);
        $request->session()->regenerate();

        return redirect()->route('yurleis.dashboardYurleis');
    }
}
