<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class MeterReadings extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'readings',
        'meter_id',
        'message',
        'timestamp'
    ];
}