<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthVerifyController extends Controller
{
    public function checkToken(){
        $access_token = session('access_token');
        $client = new Client();

        try {
            $responseee = $client->get('http://app:9000/api/auth/protected-endpoint', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ]);
            $GetInfo = json_decode($responseee->getBody()->getContents(), true);
            return response()->json(['status' =>true,'data'=>$GetInfo],200);

            // Handle the response from the protected endpoint
            // ...
        } catch (Exception $e) {
            return response()->json(['status' =>false],401);
        }
    }
}
