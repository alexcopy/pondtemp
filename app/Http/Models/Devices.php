<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{

    protected $fillable = [
        'deviceName',
        'deviceType',
        'description',
    ];
}