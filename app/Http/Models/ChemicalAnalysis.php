<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class ChemicalAnalysis extends Model
{

    protected $fillable = [
        'date',
        'nO2',
        'nO3',
        'nH4',
        'ph',
    ];
}