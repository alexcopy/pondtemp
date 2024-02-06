<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterTempSensor extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'temp_current',
        'device_id',
        'temp_unit_convert',
        'humidity_value',
        'bright_value',
        'temp_calibration',
        'timestamp'
    ];
}
