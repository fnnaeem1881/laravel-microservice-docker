<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $accessToken = Session::get('access_token');

        if ($accessToken) {
            // User is logged in, continue with the request
            $expirationTime = Session::get('expiration_time');

            // Check if the access token has expired
            if (time() >= $expirationTime) {
                // Access token has expired, perform the necessary actions
                // For example, redirect the user to the login page or refresh the access token
                Session::forget('access_token');
                Session::remove('access_token');
                return redirect('/login')->with('error', 'Access token has expired');
            }else{
                return $next($request);
            }
        } else {
            // User is not logged in, redirect to the login page or return an error response
            return redirect('/login');
        }
    }
}
