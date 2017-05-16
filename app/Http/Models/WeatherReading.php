<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 13/04/2017
 * Time: 22:24
 */

namespace App\Http\Models;


use Carbon\Carbon;
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

    public static function restoreFromTextFile(array $data)
    {
        self::create([
            'readingDate' =>$data['date'],
            'pond' => (double)$data['pt'],
            'shed' => (double)$data['sht'],
            'street' => (double)$data['strT'],
            'shedhumid' => (double)$data['humid'],
            'streethumid' => 0,
            'room' => 0,
            'roomhumid' => 0,
            'location' => 0,
            'timestamp' => $data['timestamp'],
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

    /**
     * @return array
     */
    public static function getWetherAverege($start, $end = null)
    {
        if (!$end) {
            $end = time();
        }
        $timeFormat = 'H:m';
        if (($end - $start) > 172800) {
            $timeFormat = 'd/m H:m';
        }
        $shedAver = [];
        $pondAver = [];
        $humAver = [];
        $weather = self::whereBetween('timestamp', [$start, $end])->orderBy('timestamp', 'desc')->get();
        $chunkSize = 2;
        if ($weather->count() > 72) {
            $chunkSize = round($weather->count() / 24, 0);
        }

        foreach ($weather->chunk($chunkSize) as $item) {
            $dateNum = round($item->count()/2, 0); //get middle date
            $vals= array_values($item->toArray());
            if(!isset($vals[$dateNum]['readingDate'])) continue;
            $date=$vals[$dateNum]['readingDate'];
            $readingDate = Carbon::parse($date)->format($timeFormat);
            $shedAver[$readingDate] = round($item->avg('shed'), 1);
            $pondAver[$readingDate] = round($item->avg('pond'), 1);
            $humAver[$readingDate] = round($item->avg('shedhumid'), 1);
        }
        $shedAver = array_reverse($shedAver);
        $pondAver = array_reverse($pondAver);
        $humAver = array_reverse($humAver);

        return [$shedAver, $pondAver, $humAver, $weather];
    }

}