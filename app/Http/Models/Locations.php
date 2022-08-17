<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{

    public $timestamps = true;
    protected $fillable = [
        'streetAddress',
        'postalCode',
        'city',
        'county'
    ];
}