<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Camalarms extends Model
{

    public $timestamps = false;
    protected $fillable = [
        "msgid",
        "alarm_msg",
        "alarm_time",
        "has_position",
        "version_num",
        "alarm_image",
        "alarm_type",
        "dev_id",
        "alarm_id",
        "alarm_level",
        "last_fresh_time",
        "image_id",
        "ip",
    ];
}