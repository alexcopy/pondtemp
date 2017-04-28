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
use Illuminate\Support\Collection;

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

    /**
     * Read values from request and write to DB
     * @param Request $request
     */
    public static function parseAndWrite(Request $request)
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

    /**
     * Validate results from data received assume data could't change on 2 units in giving time slot
     *
     * @param Request $request
     * @param Collection $prevResults
     * @return array
     */

    public static function validateValues(Request $request, Collection $prevResults)
    {

        $fieldCoeff = [
            'shedtemp' => ['dbfield' => 'shed', 'time' => 120, 'isValid' => true],
            'ptemp' => ['dbfield' => 'pond', 'time' => 3600, 'isValid' => true],
            'strtemp' => ['dbfield' => 'street', 'time' => 1800, 'isValid' => true],
            'shedhumid' => ['dbfield' => 'shedhumid', 'time' => 3600, 'isValid' => true],
            'roomtemp' => ['dbfield' => 'room', 'time' => 600, 'isValid' => true],
            'streethumid' => ['dbfield' => 'streethumid', 'time' => 600, 'isValid' => true],
            'roomhumid' => ['dbfield' => 'roomhumid', 'time' => 3600, 'isValid' => true],
        ];

        array_walk($fieldCoeff, function ($reqField, $values) use ($request, $prevResults) {
            $reqVal = (int)$request->get($reqField, 0);
            $prevValue = $prevResults->$values['dbfield'];
            $dbTimestamp = $prevResults->timestamp;

            $valDiff = abs((abs($prevValue) - abs($reqVal)));
            $timeDiff = time() - $dbTimestamp;

            if ($valDiff > 2 && ($timeDiff > $values['time'])) {
                $values['isValid'] = false;
            }
        });
        return $fieldCoeff;
    }


}