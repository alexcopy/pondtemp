<?php

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
        'streettemp',
        'shedtemp',
        'shedhumid',
        'streethumid',
        'room',
        'roomhumid',
        'location',
        'pressure',
        'timestamp',
        'userId'];

    public $timestamps = false;

    /**
     * Read values from request and write to DB
     * @param Request $request
     */
    public static function parseAndWrite(Request $request)
    {
        $strTemp = (int)$request->get('chkstr') ? (double)$request->get('chkstr') : (double)$request->get('strT');
        self::create([
            'readingDate' => (new DateTime())->format('Y-m-d H:i:s'),
            'pond' => (double)$request->get('ptemp', 0),
            'shedtemp' => (double)$request->get('shedtemp', 0),
            'streettemp' => $strTemp,
            'shedhumid' => (double)$request->get('shedhumid', 0),
            'streethumid' => (double)$request->get('streethumid', 0),
            'room' => (double)$request->get('roomtemp', 0),
            'roomhumid' => (double)$request->get('roomhumid', 0),
            'pressure' => (double)$request->get('press', 0),
            'location' => (string)$request->get('location', 0),
            'timestamp' => time(),
            'userId' => 10
        ]);
    }

    public static function restoreFromTextFile(array $data)
    {
        self::create([
            'readingDate' => $data['date'],
            'pond' => (double)$data['pt'],
            'shedtemp' => (double)$data['sht'],
            'streettemp' => ((int)$data['chkstr']) ? (int)$data['chkstr'] : (double)$data['strT'],
            'shedhumid' => (double)$data['humid'],
            'streethumid' => (double)$data['strH'],
            'room' => 0,
            'roomhumid' => 0,
            'location' => 0,
            'pressure' => (double)$data['press'],
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
            'shedtemp' => ['dbfield' => 'shedtemp', 'time' => 120, 'isValid' => true],
            'ptemp' => ['dbfield' => 'pond', 'time' => 3600, 'isValid' => true],
            'strtemp' => ['dbfield' => 'streettemp', 'time' => 1800, 'isValid' => true],
            'shedhumid' => ['dbfield' => 'shedhumid', 'time' => 3600, 'isValid' => true],
            'roomtemp' => ['dbfield' => 'room', 'time' => 600, 'isValid' => true],
            'streethumid' => ['dbfield' => 'streethumid', 'time' => 600, 'isValid' => true],
            'roomhumid' => ['dbfield' => 'roomhumid', 'time' => 3600, 'isValid' => true],
            'pressure' => ['dbfield' => 'pressure', 'time' => 3600, 'isValid' => true],
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
        $shedAver = [];
        $humShed = [];
        $humStr = [];
        $pondAver = [];
        $pressure = [];
        $shedTemp = [];


        if (!$end) {
            $end = time();
        }
        $timeFormat = 'H:i';
        if (($end - $start) > 172800) {
            $timeFormat = 'd/m H:i';
        }

        $weather = self::whereBetween('timestamp', [$start, $end])->orderBy('timestamp', 'desc')->get();

        if (!$weather->count()) {
            return [$shedAver, $pondAver, $humShed, $humStr, $pressure, $shedTemp, $weather];
        }

        $lastTen = $weather->take(10);
        $chunkSize = 4;
        if ($weather->count() > 120) {
            $chunkSize = round($weather->count() / 30, 0);
        }

        $lastTenDate = Carbon::parse($lastTen->first()->readingDate)->format($timeFormat);
        $shedAver [$lastTenDate] = round($lastTen->avg('streettemp'), 1);
        $pondAver [$lastTenDate] = round($lastTen->avg('pond'), 1);
        $humShed [$lastTenDate] = round($lastTen->avg('shedhumid'), 1);
        $humStr [$lastTenDate] = round($lastTen->avg('streethumid'), 1);
        $shedTemp [$lastTenDate] = round($lastTen->avg('shedtemp'), 1);
        $pressure [$lastTenDate] = round(self::convertToMercuryScale($lastTen->avg('pressure')), 1);

        foreach ($weather->chunk($chunkSize) as $item) {
            $dateNum = round($item->count() / 2, 0); //get middle date
            $vals = array_values($item->toArray());
            if (!isset($vals[$dateNum]['readingDate'])) continue;
            $date = $vals[$dateNum]['readingDate'];
            $readingDate = Carbon::parse($date)->format($timeFormat);
            $shedAver[$readingDate] = round($item->avg('streettemp'), 1);
            $pondAver[$readingDate] = round($item->avg('pond'), 1);
            $humShed[$readingDate] = round($item->avg('shedhumid'), 1);
            $humStr[$readingDate] = round($item->avg('streethumid'), 1);
            $pressure[$readingDate] = round(self::convertToMercuryScale($item->avg('pressure')), 1);
            $shedTemp[$readingDate] = round($item->avg('shedtemp'), 1);
        }

        $shedAver = array_reverse($shedAver);
        $pondAver = array_reverse($pondAver);
        $humShed = array_reverse($humShed);
        $humStr = array_reverse($humStr);
        $pressure = array_reverse($pressure);
        $shedTemp = array_reverse($shedTemp);

        return [$shedAver, $pondAver, $humShed, $humStr, $pressure, $shedTemp, $weather];
    }

    protected static function convertToMercuryScale($pascalValue)
    {
        return (double) $pascalValue / 1.3332239;
    }
}