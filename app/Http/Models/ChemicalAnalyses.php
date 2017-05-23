<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class ChemicalAnalyses extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'date',
        'nO2',
        'nO3',
        'nH4',
        'ph',
    ];
}