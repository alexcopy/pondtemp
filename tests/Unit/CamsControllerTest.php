<?php

namespace App\Http\Controllers;


use App\Http\Models\Cameras;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CamsControllerTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
        if (!File::exists(storage_pt('ftp'))) {
            File::makeDirectory(storage_pt('ftp'));
        }
        echo 'Creating directory in storage at UP '. storage_pt('ftp')."\n";
    }


    public function testDestroyCamFolder()
    {
        $camName = 'testCamName';
        $archivePath = storage_pt('ftp/archive/');
        $path = storage_pt('ftp/' . $camName);
        Cameras::makePathForCam($camName);
        self::assertDirectoryExists($path);
        self::assertIsReadable($path);
        Cameras::destroyCamFolder($camName);
        self::assertDirectoryExists($archivePath);
        self::assertIsWritable($archivePath);
        self::assertDirectoryExists($archivePath . $camName);
        self::assertDirectoryNotExists($path);
        File::deleteDirectory($archivePath);
        self::assertDirectoryNotExists($archivePath);
    }

    public function testMakePathForCam()
    {
        $camName = 'testCamName';
        $path = storage_pt('ftp/' . $camName);
        Cameras::makePathForCam($camName);
        self::assertDirectoryExists($path);
        self::assertDirectoryExists($path . '/' . 'today');
        self::assertIsWritable($path);
        self::assertIsReadable($path);
        File::deleteDirectory($path);
        self::assertDirectoryNotExists($path);
    }

    public function testRenameCamsFolder()
    {
        $oldCamName = 'oldCamName';
        $newCamName = 'newCamName';
        $path = storage_pt('ftp/');

        Cameras::makePathForCam($oldCamName);
        self::assertDirectoryExists($path . $oldCamName);
        self::assertDirectoryNotExists($path . $newCamName);

        Cameras::renameCamsFolder($oldCamName, $newCamName);
        self::assertDirectoryNotExists($path . $oldCamName);
        self::assertDirectoryExists($path . $newCamName);

        File::deleteDirectory($path . $newCamName);
        File::deleteDirectory($path . $oldCamName);

        self::assertDirectoryNotExists($path . $oldCamName);
        self::assertDirectoryNotExists($path . $newCamName);

    }


}

function storage_pt($path = '')
{
    return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}