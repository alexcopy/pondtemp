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
use phpDocumentor\Reflection\Types\Self_;

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
            $filesPath = $ftpDir . '/' . $dir->realpath . '/today';
            if (!File::exists($ftpDir . '/' . $dir->realpath)) {
                Cameras::makePathForCam($dir->realpath);
            }
            $dirFiles['files'][$dir->name] = File::allFiles($filesPath);
            $dirFiles['dirs'][$dir->name] = File::directories($ftpDir . '/' . $dir->realpath);
            $dirFiles['changed'][$dir->name] = File::lastModified($filesPath);
            $dirFiles['size'][$dir->name] = self::human_folderSize($filesPath);
        }
        $tableStats = [];
        foreach ($camIdsList as $item) {
            $tableStats[$item->cam_id] = [
//                'processed' => Camalarms::where('dev_id', $item->cam_id)->where('processed', 1)->count(),
//                'waiting' => Camalarms::where('dev_id', $item->cam_id)->where('processed', 0)->whereBetween('process_fail', [0, 9])->count(),
//                'zerofail' => Camalarms::where('dev_id', $item->cam_id)->where('process_fail', 0)->count(),
//                'overFive' => Camalarms::where('dev_id', $item->cam_id)->whereBetween('process_fail', [4, 9])->count(),
//                'tenFail' => Camalarms::where('dev_id', $item->cam_id)->where('process_fail', '>', 9)->count(),
//                'total' => Camalarms::where('dev_id', $item->cam_id)->count(),
            ];
        }

        return view('pages.camfiles', compact(['dirFiles', 'ftpDir', 'tableStats']));
    }

    protected function showFiles($filesPath, Request $request)
    {
        $pagesize = 60;
        $page = $request->get('page', 0);
        $camFiles = new CamAlarmFilesFilters;
        $title = '  Show Folder ' . $request->get('folder', null)
            . '  and subfolder '
            . $request->get('subfolder', null) . ' ';
        $pictures = $camFiles->sortFiles($filesPath, $pagesize, $page, [
            'query' => $request->toArray(),
            'path' => '/' . $request->path(),
        ]);
        return view('pages.camssnapshots', compact(['pictures', 'title']));
    }

    protected function showFolders($folderPath, Request $request)
    {
        $pageSize = 10;
        $folderName = $request->get('folder', null);
        $page = $request->get('page', 0);
        $camFiles = new CamAlarmFilesFilters;
        $sortedFolders = $camFiles->sortFolders($folderPath);
        $result = $camFiles->paginate($sortedFolders, $pageSize, $page, [
            'query' => $request->toArray(),
            'path' => '/' . $request->path(),
        ]);
        return view('pages.deatails', compact(['result', 'folderName']));
    }

    public function allFilesDetails(Request $request)
    {
        $query = $request->get('q', null);
        $camname= $request->get('folder', null);
        $camera =Cameras::where('name', $camname)->get()->first();
        $folder =optional($camera)->realpath;
        $subfolder = $request->get('subfolder', null);
        if (!$query || (!$folder)) {
            throw new PageNotFound('please specify query');
        }

        try {
            $filesPath = storage_path('ftp/' . $folder);
            if ($query == 'showtoday') {
                return $this->showFiles($filesPath . '/today', $request);
            } elseif ($query == 'showfolders') {
                return $this->showFolders($filesPath, $request);
            } elseif ($query == 'showfolderfiles') {
                return $this->showFiles($filesPath . '/' . $subfolder, $request);
            }
        } catch (\Exception $exception) {
            throw new PageNotFound($exception->getMessage());
        }
        throw new \Exception('Something went very wrong');
    }

    public static function human_folderSize($path, $h = 'h', $total = ' ')
    {
// $total='--total'
        if(!in_array(trim($total), [' ', '', '--total'])) return '0 KB';
        $io = exec('/usr/bin/du -sk' . $h . '  ' . $total . ' ' . $path);
        $sizes = explode("\t", $io);
        return $sizes[0];
    }

    public static function getHumanFoldersSize(array $folders)
    {
        if (empty($folders)) return '0 kb';
        $path = implode('  ', $folders);
        return self::human_folderSize($path, 'h', ' --total');
    }
}