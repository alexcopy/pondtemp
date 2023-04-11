<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PondSwitch extends Model
{
    use HasFactory;

    protected $fillable = [
        "switch_id",
        "status",
        "cur_power",
        "add_ele",
        "cur_current",
        "cur_voltage",
        "from_main",
        "relay_status",
        "timestamp",
    ];


    static public function check_duplicates($switch_id, array $params): int
    {
        if (isset($params['timestamp'])) {
            unset($params['timestamp']);
        }
        if (isset($params['switch_1'])) {
            unset($params['switch_1']);
        }
        $query = DB::table('pond_switches');
        foreach ($params as $field => $value) {
            $query->where($field, $value);
        }
        $results = optional($query->get()->last())->id;
        $last_rec = PondSwitch::where('switch_id', $switch_id)->latest()->first()->id;
        if ($last_rec == $results)
            return $last_rec;
        return 0;
    }
}
