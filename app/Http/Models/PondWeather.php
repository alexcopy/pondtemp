<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PondWeather extends Model
{
    use HasFactory;

    protected $fillable = [
        'town',
        'temperature',
        'wind_speed',
        'visibility',
        'uv_index',
        'humidity',
        'precipitation',
        'pressure',
        'type',
        'wind_direction',
        'feels_like',
        'description',
        'timestamp',
    ];

}
