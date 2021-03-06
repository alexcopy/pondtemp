<?php

namespace App\Http\Models;


use function GuzzleHttp\Promise\all;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MeterReadings extends Model
{

    public $timestamps = true;

    protected $fillable = [
        'readings',
        'meter_id',
        'message',
        'timestamp',
        'created_at'
    ];

    /**
     * @return array
     */
    public static function meterValuesStructured($pageSize=20): array
    {
        $prevValue = 0;
        $oldTime = 0;
        $allValues = MeterReadings::where('is_disabled', '=', '0')->orderBy('timestamp', 'desc')->paginate($pageSize);
        $meterValues = MeterReadings::where('is_disabled', '=', '0')->orderBy('timestamp', 'asc')->get() ;
        $meterValues ->each(function (&$item, $key) use (&$prevValue, &$oldTime) {
                if ($oldTime == 0)
                    $item->diff = 0;
                else
                    $item->diff = round(($item->readings - $prevValue) * 1000, 2);

                $oldTime = $oldTime == 0 ? $item->timestamp : $oldTime;
                $hours = ($item->timestamp - $oldTime) / 3600;
                if ($hours !== 0)
                    $item->perHour = round($item->diff / $hours, 2);
                else
                    $item->perHour = 0;
                $oldTime = $item->timestamp;
                $prevValue = $item->readings;
            });
        return [$allValues, $meterValues];
    }

    public static function averageWaterCalculator($interval)
    {
        $weekInterval = self::where('is_disabled',  '0')->whereBetween('timestamp', [time() - $interval, time()])->orderBy('timestamp', 'DESC');
        $min = $weekInterval->min('readings');
        $max = $weekInterval->max('readings');
        $used = ($max - $min) * 1000;
        $timeInterval = $weekInterval->max('timestamp') - $weekInterval->min('timestamp');
        if (($timeInterval + 3600) < $interval)
            $interval = $timeInterval;
        $days = intdiv($interval, 86400);
        $hours = intdiv($interval, 3600);
        $aveInDays = $days > 0 ? round($used / $days, 0) : $used;
        $aveInHours = $hours > 0 ? round($used / $hours, 2) : $used;
        return ['daily' => $aveInDays, 'hourly' => $aveInHours, 'used' => $used, 'interval' => $timeInterval];
    }
}