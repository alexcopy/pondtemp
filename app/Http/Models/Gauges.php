<?php

namespace App\Http\Models;


use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Gauges extends Model
{

    public $timestamps = false;
    protected $fillable = ['readingDate', 'pondLower', 'pondUpper', 'fl1', 'fl2','strlow', 'fl3','timestamp'];


    public static function writeToDb(Request $request )
    {

        self::create([
            'readingDate' => (new DateTime())->format('Y-m-d H:i:s'),
            'pondLower' => $request->get('pndlow', -1),
            'pondUpper' => !$request->get('pndlvl', -1),
            'fl1' => $request->get('fltr1', -1),
            'fl2' => $request->get('fltr2', -1),
            'fl3' => $request->get('fltr3', -1),
            'strlow' => $request->get('strlow', -1),
            'timestamp' => time(),
        ]);
    }

}

