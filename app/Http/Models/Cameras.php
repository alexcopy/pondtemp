<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cameras extends Model
{

    protected $fillable = [
        'cam_id',
        'name',
        'login',
        'password',
        'alarmServerUrl',
        'port',
        'channel',
        'clientExistsUrl',
        'is_cloudBased',
    ];

    public static function parseRequest(Request $request)
    {
        return [
            'cam_id' => (int)$request->input('cam_id', 0) ?: rand(1111, 7777),
            'name' => $request->input('name'),
            'login' => $request->input('login'),
            'password' => $request->input('name'),
            'alarmServerUrl' => $request->input('name'),
            'port' => (int)$request->input('port', 0) ?: 8888,
            'channel' => $request->input('channel'),
            'clientExistsUrl' => $request->input('clientExistsUrl'),
            'is_cloudBased' => $request->input('is_cloudBased'),
        ];
    }
}
