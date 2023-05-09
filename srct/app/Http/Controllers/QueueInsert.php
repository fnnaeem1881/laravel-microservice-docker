<?php

namespace App\Http\Controllers;

use App\Models\queue_insert;
use Illuminate\Http\Request;

class QueueInsert extends Controller
{
    public function store($data){
        $store= new queue_insert();
        $store->data=$data;
        $store->save();
        return response()->json(['status'=>'success']);
    }
}
