<?php

namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FishFeed extends Model
{

    protected $fillable = [
        'pond_id',
        'food_type',
        'weight',
        'description',
        'is_disabled',
        'timestamp'
    ];


    public static function metersAndFeedCombined($fromTime, $toTime)
    {
        //to get prev values and compute water usage
        $saveIntervalForReading = 86400*2;

        $fishFeeds = FishFeed::select(['timestamp', 'food_type', 'weight', 'created_at'])->whereBetween('timestamp', [$fromTime, $toTime])->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d/m');
        });

        MeterReadings::whereBetween('timestamp', [$fromTime - $saveIntervalForReading, $toTime])->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d-m-Y');
        })->each(function ($val, $key) use (&$maxValue) {
            $timestamp = Carbon::parse($key)->timestamp;
            $maxValue[$timestamp] = $val->max('readings');
        });

        // to be sure what we're starting from lowest value
        ksort($maxValue);
        $prevValue = 0;
//combine feed and meters
        array_walk($maxValue, function ($val, $key) use (&$prevValue, &$meterReadingsSorted, $fishFeeds) {
            $date = Carbon::createFromTimestamp($key)->format('d/m');
            $feed = $fishFeeds->get($date);

            if ($feed !== null) {
                $pelets = $feed->where("food_type", "pellets")->sum('weight');
                $sinking = $feed->where("food_type", "sinkpellets")->sum('weight');
                $peletsCount = $feed->where("food_type", "pellets")->count();
                $sinkingCount = $feed->where("food_type", "sinkpellets")->count();
            } else {
                $pelets = $sinking = $peletsCount = $sinkingCount = 0;
            }
            $meterReadingsSorted[$date] = [
                'readings' => $val,
                'used' => round($val - $prevValue, 5),
                'pellets' => ['weight' => $pelets, 'count' => $peletsCount],
                    'sinkpellets' => ['weight' => $sinking, 'count' => $sinkingCount],
                    ];

            $prevValue = $val;
        });

        return collect($meterReadingsSorted)->reverse();
    }
}
