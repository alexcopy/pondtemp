<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class FishFeed extends Model
{
    protected $fillable = [
        'pond_id',
        'food_type',
        'weight',
        'description',
        'is_disabled',
        'timestamp'
    ];
}
