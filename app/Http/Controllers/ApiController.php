<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function tempdata(Request $request)
    {
        $ptemp = (double)$request->get('ptemp', 0);
        $shedtemp = (double)$request->get('shedtemp', 0);
        $strtemp = (double)$request->get('strtemp', 0);
        $shedhumid = (double)$request->get('shedhumid', 0);
        $streethumid = (double)$request->get('streethumid', 0);

        $date = new DateTime();

        $fileContent = $date->format('Y-m-d H:i:s') . "  pond temp: " . $ptemp . " shedTemp: " . $shedtemp . " streetTemp:" . $strtemp . " shedhumid:" . $shedhumid . " streethumid: " . $streethumid . "\n";

        $fileStat = file_put_contents( storage_path()."/temp.txt", $fileContent, FILE_APPEND);

        if ($fileStat) {

            echo "SUCCESS";

        } else {

            echo "FAIL";

        }

    }
}
