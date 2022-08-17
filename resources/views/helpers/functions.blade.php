<?php
function humanize_size($bytes, $decimals = 2)
{
    $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' '. $size[$factor];
}

/**
 * @param $path
 * @return string
 */
function getSize($path)
{
    return App\Http\Controllers\PageController::human_folderSize($path);
}

function getQty($path)
{
    $files = Illuminate\Support\Facades\File::allFiles($path);
    return count($files);
}

function getFolderSize($path)
{
    return (int)App\Http\Controllers\PageController::human_folderSize($path, '');
}