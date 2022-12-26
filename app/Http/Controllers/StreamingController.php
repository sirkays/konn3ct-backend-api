<?php

namespace App\Http\Controllers;

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
            'type' => 'required',
            'key' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $room = RoomModel::find($input['room_id']);

        if (!$room) {
            return back()->with('error', 'Invalid Room ID!');
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
            return back()->with('error', 'Invalid type');
        }

        $payload = '{
    "identifier": "' . $identifier . '",
    "meetingID": "' . $meetingID . '",
    "rmtpURL" : "' . $url . '"
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

            return redirect()->route('streamList')->with('success', 'Streaming started successfully');
        } else {
            return back()->with('error', 'Error starting streaming');
        }
    }

    function stopStreaming($id)
    {

        $st = Streaming::find($id);

        if (!$st) {
            return back()->with('error', 'Invalid Streaming ID!');
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
            return back()->with('success', 'Streaming stopped successfully');
        } else {
            return back()->with('error', 'Error stopping streaming');
        }
    }

    function list()
    {
        $datas['streams'] = Streaming::where('user_id', Auth::id())->with('room')->latest()->paginate(20);
        $datas['i'] = 1;
        return view('user.streamings', $datas);
    }
}
