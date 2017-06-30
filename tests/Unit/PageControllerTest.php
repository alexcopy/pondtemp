<?php

namespace App\Http\Controllers;

class PageControllerTest extends \PHPUnit_Framework_TestCase
{
protected $ftppath='';
    public function setUp()
    {
        $dirs = ['dir1', 'dir2', 'dir3'];
        $this->ftppath='../storage/ftp';

        foreach ($dirs as $dir) {
            if (!file_exists($this->ftppath.'/today')) {
                $path = $this->ftppath . '/' . $dir . '/today';
                mkdir($path, $mode = 0777, $recursive = true);
            }
        }
    }

    public function testAllCamFiles()
    {
        $dirs = ['dir1', 'dir2', 'dir3'];

        $files=[];
        foreach ($dirs as $dir) {
            self::assertDirectoryExists($this->ftppath . '/' . $dir . '/today');
            foreach (range(1, 5) as $item) {
                $files[]=\Faker\Provider\File::file( '/tmp',$this->ftppath . '/' . $dir . '/today', $fullPath = true);
            }
        }

        foreach ($files as $file) {
            self::assertFileExists($file);
        }
    }

    public function tearDown()
    {
        $dirs = ['dir1', 'dir2', 'dir3'];


        foreach ($dirs as $dir) {
            if (file_exists($this->ftppath.'/'.$dir)) {
                self::delTree($this->ftppath . '/' . $dir);
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
