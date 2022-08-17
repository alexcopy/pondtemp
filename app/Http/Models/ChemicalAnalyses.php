<?php

namespace App\Http\Models;


use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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


    public static function parseAndWrite(Request $request)
    {
        self::create([
            'date' =>(new DateTime())->format('Y-m-d H:i:s'),
            'nO2' => (double)$request->get('nO2'),
            'nO3' => (double)$request->get('nO3'),
            'nH4' => (double)$request->get('nH4'),
            'ph' => (double)$request->get('ph')
        ]);
    }
}