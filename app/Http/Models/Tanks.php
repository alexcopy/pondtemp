<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Tanks extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'tankName',
        'description',
    ];

}