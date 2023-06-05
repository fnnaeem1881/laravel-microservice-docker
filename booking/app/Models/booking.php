<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    protected $guarded=[];
    protected $fillable = [
        'user_id',
        'pcik_up_location',
        'drop_off_location',
        'pcik_up_date',
        'drop_off_date',
        'pcik_up_time',
        'car',
    ];
}
