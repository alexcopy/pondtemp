<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerDevice extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'type',
        'average',
        'timestamp',
        'created_at'
    ];
}
