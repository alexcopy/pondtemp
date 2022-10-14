<?php

namespace App\Http\Services;


use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class CamAlarmFilesFilters
{
    

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



}
