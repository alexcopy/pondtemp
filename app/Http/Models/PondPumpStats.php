<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PondPumpStats extends Model
{
    use HasFactory;
    protected $fillable = [
        'Power',
        'Fault',
        'feeding',
        'flow_speed',
        'mode',
        'power_show',
        'from_main',
        'voltage',
        'rotating_speed',
        'timer_power',
        'timestamp',
        'device_id',
    ];
}
