<?php


namespace App\Http\Controllers;


use App\Exceptions\PageNotFound;
use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;
use App\Http\Models\WeatherReading;
use App\Http\Services\CamAlarmFilesFilters;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(100)->get();
        $tempReader = TempMeter::orderBy('timestamp', 'desc')->limit(100)->get();
        $guages = Gauges::orderBy('timestamp', 'desc')->limit(100)->get();
        return view('pages.index', compact(['weather', 'tempReader', 'guages']));
    }

    public function ping(Request $request)
    {
        $time = time();
        $fileContent = (new DateTime())->format('Y-m-d H:i:s')
            . "  lastaccess: " . (double)$request->get('lastaccess', 0)
            . "  current: " . $time
            . "\n";

        $fullUri = $time . ' | ' . $request->fullUrl();
        try {
            file_put_contents(storage_path() . "/pingurilog.txt", $fullUri . "\n", FILE_APPEND);
            file_put_contents(storage_path() . "/ping.txt", $fileContent, FILE_APPEND);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        echo $time;
    }

    public function graph(Request $request)
    {
        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(10)->get();
        return view('pages.graph', compact(['weather']));
    }

    public function allCamFiles(Request $request)
    {
        $dirList = explode(',', env('CAMS', ','));
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
        $limit = 15;
        $query = $request->get('q', null);
        $folder = $request->get('folder', null);
        $page = $request->get('page', 0);
        $allowedFolders = explode(',', env('CAMS', ','));
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
                $result = CamAlarmFilesFilters::fileNameIsKeyToSort($result, $folder);
            } elseif ($query == 'showfolders') {
                $title = 'Show Archived folders for ' . $folder;
                $result = self::sortFolders(File::directories($filesPath));
            } elseif ($query == 'showfolderfiles') {
                if (!$subfolder) throw new PageNotFound('Please specify subfolder');
                $title = 'Show Folder Files for ' . $folder . ' and subfolder ' . $subfolder;
                $result = File::allFiles($filesPath . '/' . $subfolder);
                $result = CamAlarmFilesFilters::fileNameIsKeyToSort($result, $folder);
            } else {
                throw new PageNotFound('didn\'t match any query params ');
            }

        } catch (\Exception $exception) {
            throw new PageNotFound($exception->getMessage());
        }


        $chunkedRes = array_chunk($result, $limit);

        if (isset($chunkedRes[$page])) {
            $result = $chunkedRes[$page];
            $page++;
        } else {
            $result = end($chunkedRes);
        }

        $next = '/' . $request->path() . "?folder={$folder}&subfolder={$subfolder}&limit={$limit}&q={$query}&page={$page}";
        return view('pages.deatails', compact(['title', 'folder', 'result', 'filesPath', 'next']));
    }

    public static function human_folderSize($path, $h = 'h')
    {

        $io = popen('/usr/bin/du -sk' . $h . ' ' . $path, 'r');
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
        krsort($folders);
        return $folders;
    }
}