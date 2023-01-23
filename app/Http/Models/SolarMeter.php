<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarMeter extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'units',
        'description',
    ];
}
