<?php

namespace App\Http\Services;


class CamAlarmFilesFilters
{


    public static function fileNameIsKeyToSort(array $alarmFiles, $camType, $direction = 'desc')
    {
        $parsedFileName = [];
        foreach ($alarmFiles as $key => $file) {
            $alarmFile = $file->getPathName();
            $keys = explode('_', $alarmFile);
            if (($camType != 'koridor') || (count($keys) <= 1)) {
                $keys = str_replace('Koridor-2017-', '', $alarmFile);
                $keys = [str_replace('-', '', $alarmFile)];
            }
            $sortArg = self::getSortArgBasedOnCamType($camType);
            if (isset($keys[$sortArg])) {
                $parsedFileName[(int)$keys[$sortArg]] = $file;
            }
        }


        if ($direction == 'desc') {
            krsort($parsedFileName);
        } else {
            ksort($parsedFileName);
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

}