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
                return $file->getCTime();
            })
            ->map(function ($file) {
                return [
                    'origPath'=>$file->getBaseName(),
                    'imgpath'=>preg_replace('~[^\.]+storage~i', '/assets/pics', $file->getPathName()),
                    'path'=>$file->getPath(),
                    'date'=>Carbon::createFromTimestamp($file->getCTime()),
                    'realPathName'=>$file->getRealPath()];
            }), $pageSize, $page, $options);
    }


    /**
     * Gera a paginação dos itens de um array ou collection.
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
}