<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class LiveStocks extends Model
{

    protected $fillable = [
        'reason',
        'description',
        'qty',
        'readings',
        'date'
    ];
}