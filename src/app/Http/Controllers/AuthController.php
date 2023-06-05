<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt_verify', ['except' => ['login']]);
    }
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'User Not Found'], 401);
        }
        return $this->respondWithToken($token);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        // Revoke the current token if it exists
        $currentAccessToken = auth()->user()->currentAccessToken();
        if ($currentAccessToken) {
            $currentAccessToken->delete();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    public function checkToken() {
        return response()->json(['success'=> true], status: 200);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
    public function protected(Request $request)
    {
        // Retrieve the authenticated user based on the provided access token
        $user = $request->user();

        // Get the user information
        $userId = $user->id;
        $email = $user->email;
        $name = $user->name;

        // ... Retrieve any other user information you need

        // Return the user information in the response
        return response()->json([
            'id' => $userId,
            'email' => $email,
            'name' => $name,
            'user' => $user,
            // ... Include any other user information in the response
        ]);
    }

}
