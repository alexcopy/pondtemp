<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PondWeather extends Model
{
    use HasFactory;

    protected $fillable = [
        'town',
        'temperature',
        'wind_speed',
        'visibility',
        'uv_index',
        'humidity',
        'precipitation',
        'pressure',
        'type',
        'town',
        'wind_direction',
        'feels_like',
        'description',
        'timestamp',
    ];

    static public function check_dublicates(array $params)
    {
        if (isset($params['timestamp'])) {
            unset($params['timestamp']);
        }
        $query = DB::table('pond_weather');
        foreach ($params as $field => $value) {
            $query->where($field, $value);
        }
        $results = $query->get()->last()->id;
        $last_rec = PondWeather::latest()->first()->id;
        if ($last_rec == $results)
            return $last_rec;
        return false;
    }

}
