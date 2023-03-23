<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
