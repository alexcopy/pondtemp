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
        $in_db = ['switch_id' => '', 'status' => '', 'add_ele' => '', 'cur_power' => '', 'cur_current' => '', 'cur_voltage' => "", 'relay_status' => "", 'from_main' => ""];
        $filtered = array_intersect_key($params, $in_db);
        $query = DB::table('pond_switches');
        foreach ($filtered as $field => $value) {
            $query->where($field, $value);
        }

        $columnNames = self::getColumnNames();
        array_walk($columnNames, function ($val) use (&$params) {
            if (!isset($params[$val])) {
                $params[$val] = 0;
            }
        });

        try {
            $results = optional($query->get()->last())->id;
            $last_rec = PondSwitch::where('switch_id', $switch_id)->latest()->first()->id;
        } catch (\Exception $e) {
            return 0;
        }
        if ($last_rec == $results)
            return $last_rec;
        return 0;
    }

    public static function getColumnNames()
    {
        $modelInstance = new static;
        $tableName = $modelInstance->getTable();
        $connection = DB::connection($modelInstance->getConnectionName());
        $columns = $connection->getSchemaBuilder()->getColumnListing($tableName);
        // Удалить стандартные поля id, created_at и updated_at
        $exceptions = ['id', 'created_at', 'updated_at', 'timestamp', 'switch_id'];
        $columns = array_diff($columns, $exceptions);

        return $columns;
    }
}
