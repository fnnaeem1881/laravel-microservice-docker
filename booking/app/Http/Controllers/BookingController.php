<?php

namespace App\Http\Controllers;

use App\Models\booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store($booking){
        $data=json_decode($booking);
        $store= new booking();
        $store->user_id=$data->id;
        $store->pcik_up_location=$data->pcik_up_location;
        $store->drop_off_location=$data->drop_off_location;
        $store->pcik_up_date=$data->pcik_up_date;
        $store->drop_off_date=$data->drop_off_date;
        $store->pcik_up_time=$data->pcik_up_time;
        $store->car=$data->car;

        $store->save();
        return response()->json(['status'=>'success','data'=>$data]);
    }
}
