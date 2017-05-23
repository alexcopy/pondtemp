<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class WaterChanges extends Model
{

    protected $fillable = [
        'changeDate',
        'description',
        'readingBefore',
        'readingAfter',
        'timestamp'
    ];
}