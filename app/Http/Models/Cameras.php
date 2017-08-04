<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Cameras extends Model
{
    protected $fillable = [
        'name',
        'login',
        'password',
        'alarmServerUrl',
        'port',
        'channel',
        'clientExistsUrl',
        'is_cloudBased',
    ];
}
