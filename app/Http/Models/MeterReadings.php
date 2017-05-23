<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class MeterReadings extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'readingDate',
        'readings'
    ];
}