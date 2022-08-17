<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PusherController extends Controller
{

    public function webhooks(Request $req)
    {
        $payload=$req->getContent();


        file_put_contents(storage_path() . "/smslog.txt", "JSON payload is: " . $payload . "\n", FILE_APPEND);
//        foreach ($request()->input('events') as $event) {
//            $channel = $event[‘channel’];
//            $user_id = $event[‘user_id’];
//            $name = $event[‘name’];

          //  $event = Pusher::event($channel, $user_id, $name);

           //if ($event->isValid()) $event->handle();
//        }

         return response("OK", 200);
    }

}
