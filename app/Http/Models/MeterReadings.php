<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class MeterReadings extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'readings',
        'meter_id',
        'message',
        'timestamp'
    ];

    /**
     * @return array
     */
    public static function meterValuesStructured(): object
    {
        $prevValue = 0;
        $oldTime = 0;
        $allValues = MeterReadings::where('is_disabled','=','0')->orderBy('id', 'asc')->get()
            ->each(function (&$item, $key) use (&$prevValue, &$oldTime) {
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
        return $allValues;
    }

}