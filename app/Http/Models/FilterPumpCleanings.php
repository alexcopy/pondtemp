<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class FilterPumpCleanings extends Model
{

    protected $fillable = [
        'cleaningDate',
        'description',
        'pumpid',
        'readings',
    ];
}