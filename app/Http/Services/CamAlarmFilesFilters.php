<?php

namespace App\Http\Services;


use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class CamAlarmFilesFilters
{


    public static function fileNameIsKeyToSort(array $alarmFiles, $camType, $direction = 'desc')
    {
        $parsedFileName = [];
        foreach ($alarmFiles as $key => $file) {
            $alarmFile = $file->getPathName();
            $keys = explode('_', $alarmFile);
            if (($camType == 'koridor') || (count($keys) <= 1)) {
                $keys = preg_replace('~Koridor-20\d\d\-~i', '', $alarmFile);
                $keys = [str_replace('-', '', $alarmFile)];
            }
            $sortArg = self::getSortArgBasedOnCamType($camType);
            if (isset($keys[$sortArg])) {
                $parsedFileName[$keys[$sortArg]] = $file;
            }
        }

        if ($direction == 'desc') {
            krsort($parsedFileName, SORT_NUMERIC);
        } else {
            ksort($parsedFileName, SORT_NUMERIC);
        }
        return $parsedFileName;
    }

    protected static function getSortArgBasedOnCamType($camType)
    {
        if (in_array($camType, ['mamacam', 'pond'])) {
            return 2;
        } elseif (in_array($camType, [32177699, 34568373])) {
            return 1;
        } else {
            return 0;
        }

    }


    public function sortFiles($dir, $pageSize=15, $page=null, $options=[])
    {
        return $this->paginate(collect(File::allFiles($dir))
            ->filter(function ($file) {
                return in_array($file->getExtension(), ['png', 'gif', 'jpg']);
            })
            ->sortBy(function ($file) {
                return $file->getMTime();
            })
            ->map(function ($file) {
                return [
                    'origPath'=>$file->getBaseName(),
                    'imgpath'=>preg_replace('~[^\.]+storage~i', '/assets/pics', $file->getPathName()),
                    'path'=>$file->getPath(),
                    'date'=>Carbon::createFromTimestamp($file->getMTime()),
                    'realPathName'=>$file->getRealPath()];
            }), $pageSize, $page, $options);
    }


    /**
     *
     * @param array|Collection      $items
     * @param int   $perPage
     * @param int  $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    public function sortFolders($filesPath)
    {
        $camFolders = File::directories($filesPath);
        $folders=[];
        foreach ($camFolders as $foldePath) {
            if (!preg_match('~day-~i', class_basename($foldePath))) continue;
            $folderName = str_replace('day-', '', class_basename($foldePath));
            $timeStamp = Carbon::parse($folderName);
            $folders[$timeStamp->timestamp] = ['date' => $timeStamp->format('d-m-Y'), 'origPath' => $foldePath, 'folder' => $folderName];
        }
        krsort($folders);
        return $folders;
    }
}
