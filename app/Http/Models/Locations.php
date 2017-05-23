<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'streetAddress',
        'postalCode',
        'city',
        'county'
    ];
}