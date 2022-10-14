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
use Illuminate\Http\Client\Response as Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Self_;
use function Sodium\add;

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
        $dirFiles = [];
        $ftpDir = [];
        $tableStats = [];

        return view('pages.camfiles', compact(['dirFiles', 'ftpDir', 'tableStats']));
    }

    protected function showFiles($filesPath, Request $request)
    {
        $pagesize = env('PER_PAGE', 60);
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

    protected function showFolders(Request $request, Response $response, $page_num)
    {
        $pageSize = env('FOLDERS_PAGE', 10);
        $json_response = $response->json();
        $folderName = $json_response['folderName'];
        $camFiles = new CamAlarmFilesFilters;
        $result = $camFiles->paginate($json_response["result"], $pageSize, $page_num, [
            'query' => $request->toArray(),
            'path' => '/' . $request->path(),
        ]);
        return view('pages.deatails', compact(['result', 'folderName']));
    }

    public function allFilesDetails(Request $request)
    {
        $query = $request->get('q', null);
        $page_size = env('PER_PAGE', 30);
        $folder = $request->get('folder', null);
        $page_num = $request->query->get('page');
        $request->query->remove('page');

        $camFiles = new CamAlarmFilesFilters;
        if (in_array($query, ['showtoday', 'showfolderfiles'])) {
            $req = Http::get(env('REMOTE_HOST') . 'allfiles/details', $request->all() + ['page_Size' => $page_size])->json();
            $resp_pict = $req['pictures'];

            $pictures = $camFiles->paginate($resp_pict['data'], $page_size, $page_num)
                ->setPath($request->url() . '?' . http_build_query($request->query()));

            $title = $req['title'];
            return view('pages.camssnapshots', compact(
                [
                    'pictures', 'title'
                ]
            ));

        } elseif ($query == 'showfolders' && $folder !== null) {
            $response = Http::get(env('REMOTE_HOST') . 'showfolder/' . $folder, $request->all() + ['page_size' => $page_size]);

            return $this->showFolders($request, $response, $page_num);
        }

    }

    public static function human_folderSize($path, $h = 'h', $total = ' ')
    {
// $total='--total'
        if (!in_array(trim($total), [' ', '', '--total'])) return '0 KB';
//        $io = exec('/usr/bin/du -sk' . $h . '  ' . $total . ' ' . $path);
//        $sizes = explode("\t", $io);
        return 0;
    }

    public static function getHumanFoldersSize(array $folders)
    {
        if (empty($folders)) return '0 kb';
        $path = implode('  ', $folders);
        return self::human_folderSize($path, 'h', ' --total');
    }
}
