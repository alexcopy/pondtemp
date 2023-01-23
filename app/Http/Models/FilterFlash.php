<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterFlash extends Model
{
    use HasFactory;
    protected $fillable=[
        'max_current',
        'meter_id',
        'duration',
    ];
}
