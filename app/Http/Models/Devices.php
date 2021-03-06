<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'pond_id',
        'type_id',
        'deviceName',
        'description',
    ];
}