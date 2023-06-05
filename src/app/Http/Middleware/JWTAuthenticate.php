<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenExpiredException) {
                //  $newToken=JWTAuth::parseIeken()->refresh();
                return response()->json(['success' => false, 'message' => 'You are UnAuthorized ', 'status' => 'expired'], 401);
            } else if ($e instanceof TokenInvalidException) {
                return response()->json(['success' > false, 'message' > 'token Invalid'],  401);
            } else {
                return response()->json(['success' => false, 'message' => 'token Not found'], status: 401);
            }
        }
        return $next($request);
    }
}
