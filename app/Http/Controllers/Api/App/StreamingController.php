<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\RoomModel;
use App\Models\Streaming;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StreamingController extends Controller
{
    function startStreaming(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'room_id' => 'required|max:255',
            'type' => 'required|in:Youtube,Facebook',
            'key' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all(),'hint'=>'Valid types are Youtube,Facebook']);
        }

        $room = RoomModel::find($input['room_id']);

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Invalid Room ID!']);
        }

        $identifier = str_replace(" ", "", $room->url) . rand();
        $key = $input['key'];
        $meetingID = "0$room->id";
        $type = $input['type'];

        if ($type == "Youtube") {
            $url = "rtmp://a.rtmp.youtube.com/live2/$key";
        } elseif ($type == "Facebook") {
            $url = "rtmps://live-api-s.facebook.com:443/rtmp/$key";
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        $payload = '{
            "identifier": "' . $identifier . '",
            "meetingID": "' . $meetingID . '",
            "rmtpURL" : "' . $url . '",
            "eurl" : "' . env('BBB_SERVER_BASE_URL') . '",
            "esalt" : "' . env('BBB_SECURITY_SALT') . '"
        }';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('KONN3CT_STREAMING_ENDPOINT') . 'start-streaming',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        Log::info("=======Start Streaming=====");
        Log::info($payload);
        Log::info($response);

        $rep = json_decode($response, true);

        if ($rep['success'] == 1) {
            Streaming::create([
                "user_id" => Auth::id(),
                "room_id" => $room->id,
                "identifier" => $identifier,
                "type" => $type,
                "stream_key" => $input['key'],
                "status" => 1
            ]);

            $user = User::find(Auth::id());
            if ($user->streaming_service == "0") {
                $user->streaming_service = Carbon::now()->addDays(8);
                $user->save();
            }

            return response()->json(['success' => true, 'message' => 'Streaming started successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Error starting streaming']);
        }
    }

    function stopStreaming($id)
    {

        $st = Streaming::find($id);

        if (!$st) {
            return response()->json(['success' => false, 'message' => 'Invalid Streaming ID!']);
        }

        $identifier = $st->identifier;

        $payload = '{
            "identifier": "' . $identifier . '"
        }';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('KONN3CT_STREAMING_ENDPOINT') . 'stop-streaming',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        Log::info("=======Stop Streaming=====");
        Log::info($payload);
        Log::info($response);

        $rep = json_decode($response, true);

        if ($rep['success'] == 1) {
            $st->status = 0;
            $st->ended_at = Carbon::now();
            $st->save();
            return response()->json(['success' => true, 'message' => 'Streaming stopped successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Error stopping streaming']);
        }
    }

    function list()
    {
        $data = Streaming::where('user_id', Auth::id())->with('room')->latest()->paginate(10);
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $data]);
    }
}
