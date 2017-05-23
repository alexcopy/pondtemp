<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class FilterPumpCleanings extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'cleaningDate',
        'description',
        'pumpid',
        'readings',
    ];
}