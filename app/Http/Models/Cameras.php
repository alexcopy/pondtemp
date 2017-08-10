<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
            'password' => $request->input('password'),
            'alarmServerUrl' => $request->input('alarmServerUrl'),
            'port' => (int)$request->input('port', 0) ?: 8888,
            'channel' => $request->input('channel'),
            'clientExistsUrl' => $request->input('clientExistsUrl'),
            'is_cloudBased' => $request->input('is_cloudBased'),
        ];
    }

    public static function makePathForCam($camName)
    {
        $path = storage_path('/ftp/' . $camName . '/today');
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true);
        }
        if (!File::isWritable($path)) {
            throw new \Exception('Folder exists but is not writable ');
        }
        if (!File::exists($path)) {
            throw new \Exception('Folder creation  has failed ');
        }
        return $path;
    }

    public static function destroyCamsFolder($camName)
    {

    }
}
