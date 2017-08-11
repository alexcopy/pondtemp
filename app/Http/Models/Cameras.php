<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        $path = storage_path('ftp/' . $camName . '/today');

        echo 'Creating directory in storage makePathForCam '.$path."\n";

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

    public static function renameCamsFolder($oldCamName, $newCamName)
    {
        $path = storage_path('ftp/');
        if (!File::exists($path . $oldCamName)) {
            throw new \Exception('File is not exists');
        }

        if (!File::copyDirectory($path . $oldCamName, $path . $newCamName, null)) {
            throw new \Exception('Error in copying directory');
        }

        if (!File::deleteDirectory($path . $oldCamName)) {
            throw new \Exception('Error in deleting old directory');
        }
        return true;
    }

    public static function destroyCamFolder($camName)
    {
        $archivePath = storage_path('ftp/archive/');
        $oldCamPath = storage_path('ftp/' . $camName);

        if (!File::exists($archivePath)) {
            try {
                File::makeDirectory($archivePath, $mode = 0777, true);
            } catch (\Exception $exception) {
                Log::crirical('Cannot create archive folder  ' . $exception->getMessage());
                return null;
            }
        }
        File::copyDirectory($oldCamPath, $archivePath . '/' . $camName, null);
        if (File::exists($archivePath . $camName)) {
            File::deleteDirectory($oldCamPath);
            if (!File::exists($oldCamPath)) {
                return true;
            } else {
                throw new \Exception('Cannot Delete old Folder');
            }
        } else {
            throw new \Exception('Cannot copy old folder to new location');
        }
    }
}
