<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class MeterReadings extends Model
{

    protected $fillable = [
        'readingDate',
        'readings'
    ];
}