<?php

namespace App\Http\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TempMeter extends Model
{

    protected $fillable = ['readingDate', 'tempVal', 'timestamp', 'userId'];
    protected $table = 'temp_meter';
    public $timestamps = true;

    public static function writeToTextFile(Request $request)
    {

        $fileContent = (new DateTime())->format('Y-m-d H:i:s')
            . "  pond temp: " . (double)$request->get('ptemp', 0)
            . "  shedTemp: " . (double)$request->get('shedtemp', 0)
            . "  chkstr: " . (double)$request->get('chkstr', 0)
            . "  streetTemp: " . (double)$request->get('strtemp', 0)
            . "  shedhumid: " . (double)$request->get('shedhumid', 0)
            . "  streethumid: " . (double)$request->get('streethumid', 0)
            . "  roomtemp : " . (double)$request->get('roomtemp', 0)
            . "  roomhumid : " . (double)$request->get('roomhumid', 0)
            . "  pressure : " . (double)$request->get('press', 0)
            . "  location : " . (string)$request->get('location', 0)
            . "\n";

        $fullUri = time() . ' | ' . $request->fullUrl();
        file_put_contents(storage_path() . "/urilog.txt", $fullUri . "\n", FILE_APPEND);
        return file_put_contents(storage_path() . "/temp.txt", $fileContent, FILE_APPEND);

    }

    public static function writeToDb($value)
    {
        self::create([
            'readingDate' => (new DateTime())->format('Y-m-d H:i:s'),
            'tempVal' => $value,
            'timestamp' => time(),
            'userId' => 10
        ]);
    }
}
