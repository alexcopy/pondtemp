<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Tanks extends Model
{

    protected $fillable = [
        'tankName',
        'tankType',
        'description',
        'timestamp',
    ];

}