<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $jwtToken = session('jwt_token');

        if ($jwtToken) {
            // User is already logged in, redirect to the dashboard or any other page
            return redirect('/dashboard');
        } else {
            // User is logged out, show the login form
            return view('auth.login');
        }
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Create a new Guzzle client
        $client = new Client();

        try {
            // Specify the URL of the destination microservice
            $destinationUrl = 'http://app:9000/api/auth/login';

            try {
                // Send a POST request to the destination microservice with the email and password in the request body
                $response = $client->post($destinationUrl, [
                    'form_params' => [
                        'email' => $email,
                        'password' => $password
                    ]
                ]);
                // Get the response body as a string
                $responseBody = $response->getBody()->getContents();
                $responseData = json_decode($responseBody, true);

                $accessToken = $responseData['access_token'];
                session(['jwt_token' => $accessToken]);

                session(['access_token' => $accessToken]);

                try {
                    $responseee = $client->get('http://app:9000/api/auth/protected-endpoint', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken
                        ]
                    ]);
                    $GetInfo = json_decode($responseee->getBody()->getContents(), true);
                    return redirect('/dashboard');

                    // Handle the response from the protected endpoint
                    // ...
                } catch (Exception $e) {
                    dd($e);
                }

                // ...
            } catch (Exception $e) {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
                // return redirect('/login')->with('error', 'Invalid credentials');
            }
        } catch (Exception $e) {
            // Handle any errors that occurred during the API call
            // Display an error message or redirect back to the login page with an error
            return redirect('/login')->with('error', 'An error occurred during login');
        }
        // $request->authenticate();

        // $request->session()->regenerate();

    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {

        // Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();
        Session::forget('access_token');
        Session::forget('jwt_token');

        return redirect('/login');
    }
}
