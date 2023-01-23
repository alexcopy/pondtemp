<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarPower extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'meter_id',
        'avg_value',
        'serialized',
        'inverter_status',
        'value_type'
    ];
}
