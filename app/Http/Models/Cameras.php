<?php

namespace App\Http\Models;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
        'is_enabled',
        'realpath',
        'description',
    ];

    public static function parseRequest(Request $request)
    {
        return [
            'cam_id' => (int)$request->input('cam_id', 0) ?: rand(1111, 7777),
            'name' => $request->input('name', null),
            'login' => $request->input('login'),
            'password' => $request->input('password'),
            'alarmServerUrl' => $request->input('alarmServerUrl'),
            'port' => (int)$request->input('port', 0) ?: 8888,
            'channel' => $request->input('channel'),
            'clientExistsUrl' => $request->input('clientExistsUrl'),
            'is_cloudBased' => $request->input('is_cloudBased'),
            'is_enabled' => $request->input('is_enabled'),
            'realpath' => $request->input('realpath'),
            'description' => $request->input('description'),
        ];
    }

    public static function makePathForCam($camName, $path = null)
    {
        $path = $path ?: storage_path('ftp/' . $camName . '/today');
        Log::alert('Creating directory in storage makePathForCam ' . $path . "\n");

        if (File::exists($path)) {
            throw new \Exception('Folder already exists');
        } else {
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

    public static function renameCamsFolder($oldCamName, $newCamName, $path = null)
    {
        $path = $path ?: storage_path('ftp/');
        if (!File::exists($path . $oldCamName)) {
            self::makePathForCam($path . $oldCamName);
        }

        if (File::exists($path . $newCamName)) {
            throw new \Exception('Cam is already exists Please choose different newcam name');
        }

        if (!rename($path . $oldCamName, $path . $newCamName)) {
            throw new \Exception('Error in copying directory');
        }

        if (File::exists($path . $oldCamName)) {
            throw new \Exception('there some other problems in renaming');
        }
        return true;
    }

    public static function destroyCamFolder($camName, $archivePath = null, $oldCamPath = null)
    {
        $archivePath = $archivePath ?: storage_path('ftp/archive/');
        $oldCamPath = $oldCamPath ?: storage_path('ftp/' . $camName);
        $time = time();
        if (!self::checkAndCreateArchiveFolder($archivePath)) {
            throw new \Exception('Cannot create archive folder please check permissions');
        }

        if (!File::exists($oldCamPath)) {
            return true;
        }
        File::copyDirectory($oldCamPath, $archivePath . '/' . $camName . "_{$time}", null);
        if (File::exists($archivePath . $camName . "_{$time}")) {
            File::deleteDirectory($oldCamPath);
            if (!File::exists($oldCamPath)) {
                return $time;
            } else {
                throw new \Exception('Cannot Delete old Folder');
            }
        } else {
            throw new \Exception('Cannot copy old folder to new location');
        }

    }

    protected static function checkAndCreateArchiveFolder($path = null)
    {
        $path = $path ?: storage_path('ftp/archive/');

        if (!File::exists($path)) {
            try {
                File::makeDirectory($path, $mode = 0777, true);
            } catch (\Exception $exception) {
                Log::crirical('Cannot create archive folder  ' . $exception->getMessage());
                return false;
            }
        }
        return File::exists($path);
    }
}
