<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


require __DIR__ . '/auth.php';

// Route::post('/custom/login', function (Request $request) {

//     // ...

//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     $email = $request->email;
//     $password = $request->password;

//     // Create a new Guzzle client
//     $client = new Client();

//     // Specify the URL of the destination microservice
//     $destinationUrl = 'http://app:9000/api/auth/login';

//     try {
//         // Send a POST request to the destination microservice with the email and password in the request body
//         $response = $client->post($destinationUrl, [
//             'form_params' => [
//                 'email' => $email,
//                 'password' => $password
//             ]
//         ]);
//         // Get the response body as a string
//         $responseBody = $response->getBody()->getContents();
//         dd($responseBody);
//         // Process the response from the destination microservice
//         // ...
//     } catch (Exception $e) {
//         dd($e);
//         // Handle any errors that occurred during the API call
//         // ...
//     }
// })->name('custom.login');


Route::get('/dashboard', function (Request $request) {
    $access_token = session('access_token');
    $client = new Client();

    try {
        $responseee = $client->get('http://app:9000/api/auth/protected-endpoint', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token
            ]
        ]);
        $GetInfo = json_decode($responseee->getBody()->getContents(), true);
        return view('dashboard', [
            'userId' => $GetInfo['id'],
            'email' => $GetInfo['email'],
            'name' => $GetInfo['name'],

        ]);
        // Handle the response from the protected endpoint
        // ...
    } catch (Exception $e) {
        dd($e);
    }

})->middleware('checklogin')->name('dashboard');

Route::get('/logout/custom',function(){
    Session::forget('access_token');
    Session::remove('access_token');

    return redirect('/login');
})->name('logout.custom');
View::composer('layouts.navigation',function ($view) {

});
