<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PusherController extends Controller
{

    public function webhooks(Request $request)
    {
        return response([], 200);
    }

}
