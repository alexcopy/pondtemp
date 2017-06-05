<?php

namespace App\Http\Controllers;

class PageControllerTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $dirs = ['dir1', 'dir2', 'dir3'];
        $ftpDir = storage_path() . '/ftp';

        foreach ($dirs as $dir) {
            if (!file_exists($ftpDir.'/today')) {
                mkdir($ftpDir . '/' . $dir . '/today', $mode = 0777, $recursive = true);
            }
        }
    }

    public function testAllCamFiles()
    {
        $dirs = ['dir1', 'dir2', 'dir3'];
        $ftpDir = storage_path() . '/ftp';
        $files=[];
        foreach ($dirs as $dir) {
            self::assertDirectoryExists($ftpDir . '/' . $dir . '/today');
            foreach (range(1, 5) as $item) {
                $files[]=\Faker\Provider\File::file( '/tmp',$ftpDir . '/' . $dir . '/today', $fullPath = true);
            }
        }

        foreach ($files as $file) {
            self::assertFileExists($file);
        }
    }

    public function tearDown()
    {
        $dirs = ['dir1', 'dir2', 'dir3'];
        $ftpDir = storage_path() . '/ftp';

        foreach ($dirs as $dir) {
            if (file_exists($ftpDir.'/'.$dir)) {
                self::delTree($ftpDir . '/' . $dir);
            }
        }
        parent::tearDown();
    }


    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
     }
}
