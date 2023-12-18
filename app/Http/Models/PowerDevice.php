<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerDevice extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'value',
        'timestamp',
        'created_at'
    ];
}
