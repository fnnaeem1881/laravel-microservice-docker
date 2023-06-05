<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthVerifyController;
use App\Jobs\booking;
use Illuminate\Http\Request;

class FrontendBookingController extends Controller
{
    public function storeBooking(Request $request)
    {
        $checkToken = (new AuthVerifyController)->checkToken();
        $status = $checkToken->original['status'];
        if ($status == true) {
            $data = [
                'pcik_up_location' => $request->pcik_up_location,
                'drop_off_location' => $request->drop_off_location,
                'pcik_up_date' => $request->pcik_up_date,
                'drop_off_date' => $request->drop_off_date,
                'pcik_up_time' => $request->pcik_up_time,
                'car' => $request->car,
                'status' =>$status,
                'id' => $checkToken->original['data']['id'],
                'name' => $checkToken->original['data']['name'],
                'email' => $checkToken->original['data']['email'],
                'user' => $checkToken->original['data']['user'],
            ];
            // dd($data);
            booking::dispatch(json_encode($data));
            return redirect()->back()->with('success','Booking Success');
        }else{
            return redirect()->back()->with('error','Booking failed');
        }
    }
}
