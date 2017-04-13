<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 13/04/2017
 * Time: 22:24
 */

namespace App\Http\Models;


use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WeatherReading extends Model
{

    protected $fillable = [
        'readingDate',
        'pond',
        'street',
        'shed',
        'shedhumid',
        'streethumid',
        'room',
        'roomhumid',
        'location',
        'timestamp',
        'userId'];

    public $timestamps = false;

    public static function readDataAndWrite(Request $request)
    {
        self::create([
            'readingDate' => (new DateTime())->format('Y-m-d H:i:s'),
            'pond' => (double)$request->get('ptemp', 0),
            'shed' => (double)$request->get('shedtemp', 0),
            'street' => (double)$request->get('strtemp', 0),
            'shedhumid' => (double)$request->get('shedhumid', 0),
            'streethumid' => (double)$request->get('streethumid', 0),
            'room' => (double)$request->get('roomtemp', 0),
            'roomhumid' => (double)$request->get('roomhumid', 0),
            'location' => (string)$request->get('location', 0),
            'timestamp' => time(),
            'userId' => 10
        ]);
    }


}