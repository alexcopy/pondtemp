<?php


namespace App\Http\Controllers;


use App\Exceptions\PageNotFound;
use App\Http\Models\Camalarms;
use App\Http\Models\Cameras;
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

        $dirList = Cameras::all();
        $camIdsList = Cameras::where('is_cloudBased', 1)->get();
        $ftpDir = storage_path('ftp');
        $dirFiles = [];
        $dirFiles['files'] = [];

        foreach ($dirList as $dir) {
            $filesPath = $ftpDir . '/' . $dir->name . '/today';
            if (!File::exists($ftpDir . '/' . $dir->name)) {
                Cameras::makePathForCam($dir->name);
            }
            $dirFiles['files'][$dir->name] = File::allFiles($filesPath);
            $dirFiles['dirs'][$dir->name] = File::directories($ftpDir . '/' . $dir->name);
            $dirFiles['changed'][$dir->name] = File::lastModified($filesPath);
            $dirFiles['size'][$dir->name] = self::human_folderSize($filesPath);
        }
        $tableStats = [];
        foreach ($camIdsList as $item) {
            $tableStats[$item->cam_id] = [
                'processed' => Camalarms::where('dev_id', $item->cam_id)->where('processed', 1)->count(),
                'waiting' => Camalarms::where('dev_id', $item->cam_id)->where('processed', 0)->whereBetween('process_fail', [0, 9])->count(),
                'zerofail' => Camalarms::where('dev_id', $item->cam_id)->where('process_fail', 0)->count(),
                'overFive' => Camalarms::where('dev_id', $item->cam_id)->whereBetween('process_fail', [4, 9])->count(),
                'tenFail' => Camalarms::where('dev_id', $item->cam_id)->where('process_fail', '>', 9)->count(),
                'total' => Camalarms::where('dev_id', $item->cam_id)->count(),
            ];
        }

        return view('pages.camfiles', compact(['dirFiles', 'ftpDir', 'tableStats']));
    }

    protected function showFiles($filesPath, Request $request)
    {
        $pagesize = 78;
        $page = $request->get('page', 0);
        $camFiles= new CamAlarmFilesFilters;
        $title = 'Show Folder <b>' . $request->get('folder', null)
            . '</b> and subfolder <b>'
            . $request->get('subfolder', null).'</b>';
        $pictures = $camFiles->sortFiles($filesPath, $pagesize, $page, [
            'query'=> $request->toArray(),
            'path'=>'/'.$request->path(),
        ]);
        return view('pages.camssnapshots', compact(['pictures', 'title']));
    }

    public function allFilesDetails(Request $request)
    {
        $limit = 70;
        $query = $request->get('q', null);
        $folder = $request->get('folder', null);
        $page = $request->get('page', 0);
        $subfolder = $request->get('subfolder', null);
        $result = [];
        $next = '';

        if (!$query || (!$folder)) {
            throw new PageNotFound('please specify query');
        }
        if (Cameras::where('name', $folder)->count() == 0) {
            throw new PageNotFound('Unrecognized Folder Name');
        }

        try {
            $filesPath = storage_path('ftp/' . $folder);
            if ($query == 'showtoday') {
                return $this->showFiles($filesPath . '/today', $request);
            } elseif ($query == 'showfolders') {
                $title = 'Show Archived folders for ' . $folder;
                $result = self::sortFolders(File::directories($filesPath));
            } elseif ($query == 'showfolderfiles') {
                return $this->showFiles($filesPath.'/' . $subfolder, $request);
            }
        } catch (\Exception $exception) {
            throw new PageNotFound($exception->getMessage());
        }

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