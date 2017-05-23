<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Chemicals extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'date',
        'qty',
        'reason',
        'type',
    ];
}