<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\RoomModel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecordingController extends Controller
{
    public function show(){

        $r2=RoomModel::where('user_id', Auth::id())->select('id')->get();

        if(count($r2) == 0){
            return response()->json(['success' => true, 'message' => 'You have no recording yet', 'data' => []]);
        }

        try {
            $fer = [];

            foreach ($r2 as $r) {
                array_push($fer, $r->id);
                array_push($fer, "0$r->id");
            }

            if (App::environment(['local', 'staging'])) {
                $datas['record'] = '[ { "recordID": "bdb4ef8138bd46aa7f134cd4c2a1dd8fc2863f06-1669190001680", "meetingID": "23", "internalMeetingID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "name": "Sammy Server Record", "isBreakout": "false", "published": "true", "state": "published", "startTime": "1604321641383", "endTime": "1604322318372", "participants": "2", "rawSize": "4549560", "metadata": { "endcallbackurl": "https:\/\/dev.konn3ct.net\/leftsession", "isBreakout": "false", "meetingId": "23", "meetingName": "Sammy" }, "size": "4158906", "playback": { "format": { "type": "presentation", "url": "https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=bdb4ef8138bd46aa7f134cd4c2a1dd8fc2863f06-1669190001680", "processingTime": "178453", "length": "10", "size": "4158906", "preview": { "images": { "image": [ "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png" ] } } } }, "data": [] }, { "recordID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "meetingID": "23", "internalMeetingID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "name": "Sammy", "isBreakout": "false", "published": "true", "state": "published", "startTime": "1604321641383", "endTime": "1604322318372", "participants": "2", "rawSize": "4549560", "metadata": { "endcallbackurl": "https:\/\/dev.konn3ct.net\/leftsession", "isBreakout": "false", "meetingId": "23", "meetingName": "Sammy" }, "size": "4158906", "playback": { "format": { "type": "presentation", "url": "https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "processingTime": "178453", "length": "10", "size": "4158906", "preview": { "images": { "image": [ "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png" ] } } } }, "data": [] }, { "recordID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517", "meetingID": "23", "internalMeetingID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517", "name": "Sammy", "isBreakout": "false", "published": "true", "state": "published", "startTime": "1604320993517", "endTime": "1604321453617", "participants": "2", "rawSize": "3500466", "metadata": { "endcallbackurl": "https:\/\/dev.konn3ct.net\/leftsession", "isBreakout": "false", "meetingId": "23", "meetingName": "Sammy" }, "size": "2074489", "playback": { "format": { "type": "presentation", "url": "https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517", "processingTime": "80959", "length": "4", "size": "2074489", "preview": { "images": { "image": [ "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-1.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-2.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-3.png" ] } } } }, "data": [] } ]';
            } else {
                $datas['record'] = \Bigbluebutton::getRecordings([
                    'meetingID' => $fer, //pass as array if get multiple recordings
                ]);
            }

            $downloads['video']=['name' => 'Video & Audio (MP4)', 'url'=>route('download.recording', ['filename' => 'replaceRecordID', 'type'=>'video'])];
            $downloads['screenshare']=['name' => 'Screenshare (MP4)', 'url'=>route('download.recording', ['filename' => 'replaceRecordID', 'type'=>'screenshare'])];
            $downloads['chats']=['name' => 'Chat(s) (TXT)', 'url'=>route('download.recording', ['filename' => 'replaceRecordID', 'type'=>'chats'])];

            return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => json_decode($datas['record'], true), 'download_links' => $downloads]);

        } catch (\Exception $e) {

            return response()->json(['success' => true, 'message' => 'Fetched successfully with error', 'data' => []]);
        }

    }

    public function delete($id){
        try {
            $bbd = \Bigbluebutton::deleteRecordings([
                'recordID' => $id,
            ]);

            Log::alert("Deleting Recording $id, requested by " . Auth::user()->email . " : " . json_encode($bbd));

            if ($bbd['returncode'] == "FAILED") {
                return response()->json(['success' => false, 'message' => $bbd['message']]);
            }

            return response()->json(['success' => true, 'message' => 'Deleted successfully']);
        }catch (\Exception $e){
            Log::error("Deleting Recording $id, requested by " . Auth::user()->email . " : " . $e);
            return response()->json(['success' => false, 'message' => 'Error deleting recording. Try again']);
        }
    }
}
