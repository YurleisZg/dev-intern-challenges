<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('Elkin*')) {
            return route('elkin.challenges.auth.login');
        }

        if ($request->is('Yurleis*')) {
            return route('yurleis.challenges.auth.login');
        }

        return route('home');
    }
}
