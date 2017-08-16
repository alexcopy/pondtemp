<?php

namespace App\Http\Controllers;


use App\Http\Models\Cameras;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CamsControllerTest extends TestCase
{

    private $testCamName = '';

    protected function setUp()
    {
        parent::setUp();
        $this->testCamName = 'testCamName';
        if (!File::exists(storage_pt('ftp'))) {
            File::makeDirectory(storage_pt('ftp'));
        }
        if (File::exists(storage_pt('ftp/' . $this->testCamName))) {
            File::deleteDirectory('ftp/' . $this->testCamName);
        }
        echo 'Creating directory in storage at UP ' . storage_pt('ftp') . "\n";
    }


    public function testDestroyCamFolder()
    {

        $archivePath = storage_pt('ftp/archive/');
        $path = storage_pt('ftp/' . $this->testCamName);
        Cameras::makePathForCam($this->testCamName, $path.'/today');
        self::assertDirectoryExists($path);
        self::assertIsReadable($path);
        Cameras::destroyCamFolder($this->testCamName);
        self::assertDirectoryExists($archivePath);
        self::assertIsWritable($archivePath);
        self::assertDirectoryExists($archivePath . $this->testCamName);
        self::assertDirectoryNotExists($path);
        File::deleteDirectory($archivePath);
        self::assertDirectoryNotExists($archivePath);
    }

    public function testMakePathForCam()
    {

        $path = storage_pt('ftp/' . $this->testCamName);
        Cameras::makePathForCam($this->testCamName, $path . '/today');
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

        Cameras::makePathForCam($oldCamName, $path . $oldCamName.'/today');
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