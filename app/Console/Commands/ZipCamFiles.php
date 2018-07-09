<?php

namespace App\Console\Commands;

use App\Http\Models\Cameras;
use Chumper\Zipper\Zipper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


class ZipCamFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zip:camfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $zipper = new Zipper;
        $dirList = Cameras::all();
        $ftpDir = storage_path('ftp');
        $logFiles = $zipper->make('test.zip')->listFiles('/\.log$/i');
        dd($logFiles);
        $dirFiles=[];
        foreach ($dirList as $dir) {
            $filesPath = $ftpDir . '/' . $dir->name . '/today';
            if (!File::exists($ftpDir . '/' . $dir->name)) {
                Cameras::makePathForCam($dir->name);
            }
            $dirFiles['files'][$dir->name] = File::allFiles($filesPath);
            $dirFiles['dirs'][$dir->name] = File::directories($ftpDir . '/' . $dir->name);
        }


//        $zipper->make('test.zip')->folder('test')->add('composer.json');
//        $zipper->zip('test.zip')->folder('test')->add('composer.json','test');
//
//        $zipper->remove('composer.lock');
//
//        $zipper->folder('mySuperPackage')->add(
//            array(
//                'vendor',
//                'composer.json'
//            ),
//);
//
//        $zipper->getFileContent('mySuperPackage/composer.json');
//
//        $zipper->make('test.zip')->extractTo('',array('mySuperPackage/composer.json'),Zipper::WHITELIST);
//
//        $zipper->close();
    }
}
