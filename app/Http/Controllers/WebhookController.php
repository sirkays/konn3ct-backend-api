<?php

namespace App\Http\Controllers;

use App\Models\Streaming;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    function meeting(Request $request)
    {
        $input = $request->all();
        $event = str_replace("-", "_", $input['event']);
        $data = json_decode($event)[0];
        if ($data->data->id == "meeting_ended") {
            $meet_id = substr($data->data->attributes->meeting->external_meeting_id, 1);

            $streamings = Streaming::where(['room_id' => $meet_id, 'status' => 1])->get();

            foreach ($streamings as $str) {
                $strc = new StreamingController();
                echo $strc->stopStreaming($str->id);
            }
            return "Streaming stopped successfully";
        } else {
            return $data->data->id . " - Well received";
        }
    }
}
