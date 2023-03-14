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
     * @param array $fields_to_validate
     * @param int $deviation
     * @return array
     * @throws Exception
     */
    public function validateInputData($input_data, array $fields_to_validate = ['power_show', 'voltage', 'rotating_speed'], int $deviation = 50)
    {
        $all_data = self::select($fields_to_validate)
            ->where([
                'flow_speed' => $input_data['flow_speed'],
                'from_main' => $input_data['from_main'],
            ])
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
                return [
                    'errors' => true,
                    'error_msg' => [
                        'field' => $field,
                        'avg_in_db' => $avg_val,
                        'avg_received' => $input_data[$field],
                        'deviation' => $deviation
                    ]
                ];
            }
        }
        return [
            'errors' => false,
            'error_msg' => 'NO ERRORS'
        ];
    }

}
