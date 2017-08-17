<?php

namespace App\Http\Controllers;

use App\Http\Models\Cameras;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CamsControllerTest extends TestCase
{

    private $testCamName = '';
    private $ftppath;

    protected function setUp()
    {
        parent::setUp();
        $this->ftppath = '../storage/ftp';
        $this->testCamName = 'testCamName';
        $newCamName = 'newCamName';
        if (!File::exists($this->ftppath)) {
            File::makeDirectory($this->ftppath, $mode = 0777, true);
        }
        if (File::exists($this->ftppath . '/' . $this->testCamName)) {
            File::deleteDirectory($this->ftppath . '/' . $this->testCamName);
        }

        if (File::exists($this->ftppath . '/' . $newCamName)) {
            File::deleteDirectory($this->ftppath . '/' . $newCamName);
        }
        $this->ftppath = '../storage/ftp/';
    }


    public function testDestroyCamFolder()
    {
        $archivePath = $this->ftppath . 'archive/';
        $path = $this->ftppath . $this->testCamName;
        Cameras::makePathForCam($this->testCamName, $path . '/today');
        self::assertDirectoryExists($path);
        self::assertIsReadable($path);
        Cameras::destroyCamFolder($this->testCamName, $archivePath, $path);
        self::assertDirectoryExists($archivePath);
        self::assertIsWritable($archivePath);
        self::assertDirectoryExists($archivePath . $this->testCamName);
        self::assertDirectoryNotExists($path);
        File::deleteDirectory($archivePath);
        self::assertDirectoryNotExists($archivePath);
    }

    public function testMakePathForCam()
    {
        $path = $this->ftppath . $this->testCamName;
        Cameras::makePathForCam($this->testCamName, $path . '/today');
        self::assertDirectoryExists($path);
        self::assertDirectoryExists($path . '/' . 'today');
        self::assertIsWritable($path);
        self::assertIsReadable($path);
        File::deleteDirectory($path);
        self::assertDirectoryNotExists($path);
    }

    /**
     * @expectedException \Exception
     */
    public function testRenameCamsFolder()
    {
        $oldCamName = 'oldCamName';
        $newCamName = 'newCamName';
        $path = $this->ftppath;
        Cameras::makePathForCam($oldCamName, $path . $oldCamName . '/today');
        self::assertDirectoryExists($path . $oldCamName);
        self::assertDirectoryNotExists($path . $newCamName);
        Cameras::renameCamsFolder($oldCamName, $newCamName, $path);
        self::assertDirectoryNotExists($path . $oldCamName);
        try {
            Cameras::renameCamsFolder($oldCamName, $newCamName, $path);

        } catch (\Exception $exception) {

            self::assertEquals('Cam folder isn\'t exists try to delete camera first', $exception->getMessage());
        }
        try {
            Cameras::renameCamsFolder($newCamName, $newCamName, $path);
        } catch (\Exception $exception) {
            self::assertEquals('Cam is already exists Please choose different newcam name', $exception->getMessage());
        }
        self::assertDirectoryExists($path . $newCamName);
        File::deleteDirectory($path . $newCamName);
        File::deleteDirectory($path . $oldCamName);
        self::assertDirectoryNotExists($path . $oldCamName);
        self::assertDirectoryNotExists($path . $newCamName);
        Cameras::renameCamsFolder($oldCamName, $newCamName, $path);
    }
}
