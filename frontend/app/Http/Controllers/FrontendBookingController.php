<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthVerifyController;
use Illuminate\Http\Request;

class FrontendBookingController extends Controller
{
    public function storeBooking(Request $request){
        $checkToken =(new AuthVerifyController)->checkToken();
        $status=$checkToken->original['status'];

        dd($status,$checkToken,$request->all(),$checkToken->original,$checkToken->original['data']['id']);
        dd($request->all());
    }
}
