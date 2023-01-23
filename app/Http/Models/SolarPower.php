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
        'hourly_value',
        'serialized',
        'value_type'
    ];
}
