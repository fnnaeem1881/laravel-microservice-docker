<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $jwtToken = session('jwt_token');

        if ($jwtToken) {
            // User is already logged in, redirect to the dashboard or any other page
            // return redirect('/dashboard');
        } else {
            // User is logged out, show the login form
            return view('auth.login');
        }

    }
}
