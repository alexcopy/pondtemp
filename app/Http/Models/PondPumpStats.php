<?php

namespace App\Http\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PondPumpStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'Power',
        'Fault',
        'feeding',
        'flow_speed',
        'mode',
        'power_show',
        'from_main',
        'voltage',
        'rotating_speed',
        'timer_power',
        'timestamp',
        'device_id',
    ];


    /**
     * @param $input_data
     * @param $fields_to_validate
     * @param $deviation
     * @return bool
     * @throws Exception
     */
    public function validateInputData($input_data, $fields_to_validate = ['power_show', 'voltage', 'rotating_speed'], $deviation = 20)
    {
        $all_data = self::select($fields_to_validate)
            ->where(['flow_speed' => $input_data['flow_speed']])
            ->offset(0)->limit(10000)->get();
        foreach ($fields_to_validate as $field) {
            $avg_val = $all_data->avg($field);
            $korridor = $avg_val * ($deviation / 100);
            $chek_val = filter_var(
                $input_data[$field],
                FILTER_VALIDATE_INT,
                array(
                    'options' => array(
                        'min_range' => $avg_val - $korridor,
                        'max_range' => $avg_val + $korridor
                    )
                )
            );
            if (!$chek_val) {
                return false;
            }

        }
        return true;
    }

}
