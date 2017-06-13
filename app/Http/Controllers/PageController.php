<?php


namespace App\Http\Controllers;


use App\Exceptions\PageNotFound;
use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;
use App\Http\Models\WeatherReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{

    public function index()
    {
        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(100)->get();
        $tempReader = TempMeter::orderBy('timestamp', 'desc')->limit(100)->get();
        $guages = Gauges::orderBy('timestamp', 'desc')->limit(100)->get();
        return view('pages.index', compact(['weather', 'tempReader', 'guages']));
    }

    public function ping()
    {
        return response()->json(time());
    }

    public function graph(Request $request)
    {
        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(10)->get();
        return view('pages.graph', compact(['weather']));
    }

    public function allCamFiles(Request $request)
    {
        $dirList = ['mamacam', 'pond', 'koridor'];
        $ftpDir = storage_path('ftp');
        $dirFiles = [];


        foreach ($dirList as $dir) {
            $filesPath = $ftpDir . '/' . $dir . '/today';

            $dirFiles['files'][$dir] = File::allFiles($filesPath);
            $dirFiles['dirs'][$dir] = File::directories($ftpDir . '/' . $dir);
            $dirFiles['changed'][$dir] = File::lastModified($filesPath);
            $dirFiles['size'][$dir] = self::human_folderSize($filesPath);

        }
        return view('pages.camfiles', compact(['dirFiles', 'ftpDir']));
    }

    public function allFilesDetails(Request $request)
    {
        $query = $request->get('q', null);
        $folder = $request->get('folder', null);
        $limit = $request->get('limit', 100);
        $allowedFolders = ['mamacam', 'pond', 'koridor'];
        $subfolder = $request->get('subfolder', null);


        if (!$query || (!$folder)) {
            throw new PageNotFound('please specify query');
        }
        if (!in_array($folder, $allowedFolders)) {
            throw new PageNotFound('Unrecognized Folder Name');
        }

        try {
            $filesPath = storage_path('ftp/' . $folder);
            if ($query == 'showtoday') {
                $title = 'Show Today Files for ' . $folder;
                $result = File::allFiles($filesPath . '/today');
            } elseif ($query == 'showfolders') {
                $title = 'Show Archived folders for ' . $folder;
                $result = self::sortFolders(File::directories($filesPath));
            } elseif ($query == 'showfolderfiles') {
                if (!$subfolder) throw new PageNotFound('Please specify subfolder');
                $title = 'Show Folder Files for ' . $folder . ' and subfolder ' . $subfolder;
                $result = File::allFiles($filesPath . '/' . $subfolder);
            } else {
                throw new PageNotFound('didn\'t match any query params ');
            }

        } catch (\Exception $exception) {
            throw new PageNotFound($exception->getMessage());
        }
        return view('pages.deatails', compact(['title', 'folder', 'result', 'filesPath']));
    }

    public static function human_folderSize($path, $decimals = 2)
    {
        $io = popen('/usr/bin/du -skh ' . $path, 'r');
        $size = fgets($io, 4096);
        $size = substr($size, 0, strpos($size, "\t"));
        pclose($io);
        return $size;

    }

    public static function sortFolders(array $foldersList)
    {
        $folders = [];
        foreach ($foldersList as $foldePath) {
            if (!preg_match('~day-~i', class_basename($foldePath))) continue;
            $folderName = str_replace('day-', '', class_basename($foldePath));
            $timeStamp = Carbon::parse($folderName);
            $folders[$timeStamp->timestamp] = ['date' => $timeStamp->format('d-m-Y'), 'origPath' => $foldePath, 'folder' => $folderName];
        }
        ksort($folders);
        return $folders;
    }
}