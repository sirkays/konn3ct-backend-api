<?php

namespace App\Http\Controllers;

use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class RecordingController extends Controller
{
    public function show(){

        $rc=RoomModel::where('user_id', Auth::id())->count();
        $r=RoomModel::where('user_id', Auth::id())->select('id')->first();
        $r2=RoomModel::where('user_id', Auth::id())->select('id')->get();
        $datas['recordings']=[];
        $datas['i']=1;

        try {
            if ($rc == 0) {
                return view('user.recording', $datas);

            } elseif ($rc == 1) {
                if (App::environment(['local', 'staging'])) {
//                    $datas['record'] = '[{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602949708825","endTime":"1602953716866","participants":"6","rawSize":"72464714","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"42442450","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","processingTime":"362454","length":"13","size":"42442450","preview":{"images":{"image":"https:\/\/konn3ct.com\/presentation\/356a192b7913b04c54574d18c28d46e6395428ab-1602949708825\/presentation\/500931cdfdbebc683e886633b3c9524653ffc9f1-1602949930341\/thumbnails\/thumb-1.png"}}}},"data":[]},{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602957298524","endTime":"1602957371601","participants":"1","rawSize":"703164","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"423931","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","processingTime":"4050","length":"0","size":"423931","preview":{"images":{"image":"https:\/\/konn3ct.com\/presentation\/356a192b7913b04c54574d18c28d46e6395428ab-1602957298524\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602957299568\/thumbnails\/thumb-1.png"}}}},"data":[]},{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602938570040","endTime":"1602938887347","participants":"1","rawSize":"1410463","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"2692654","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","processingTime":"50769","length":"4","size":"2692654"}},"data":[]}];';
                    $datas['record'] = '[ { "recordID": "bdb4ef8138bd46aa7f134cd4c2a1dd8fc2863f06-1669190001680", "meetingID": "23", "internalMeetingID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "name": "Sammy Server Record", "isBreakout": "false", "published": "true", "state": "published", "startTime": "1604321641383", "endTime": "1604322318372", "participants": "2", "rawSize": "4549560", "metadata": { "endcallbackurl": "https:\/\/dev.konn3ct.net\/leftsession", "isBreakout": "false", "meetingId": "23", "meetingName": "Sammy" }, "size": "4158906", "playback": { "format": { "type": "presentation", "url": "https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=bdb4ef8138bd46aa7f134cd4c2a1dd8fc2863f06-1669190001680", "processingTime": "178453", "length": "10", "size": "4158906", "preview": { "images": { "image": [ "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png" ] } } } }, "data": [] }, { "recordID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "meetingID": "23", "internalMeetingID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "name": "Sammy", "isBreakout": "false", "published": "true", "state": "published", "startTime": "1604321641383", "endTime": "1604322318372", "participants": "2", "rawSize": "4549560", "metadata": { "endcallbackurl": "https:\/\/dev.konn3ct.net\/leftsession", "isBreakout": "false", "meetingId": "23", "meetingName": "Sammy" }, "size": "4158906", "playback": { "format": { "type": "presentation", "url": "https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383", "processingTime": "178453", "length": "10", "size": "4158906", "preview": { "images": { "image": [ "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png" ] } } } }, "data": [] }, { "recordID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517", "meetingID": "23", "internalMeetingID": "d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517", "name": "Sammy", "isBreakout": "false", "published": "true", "state": "published", "startTime": "1604320993517", "endTime": "1604321453617", "participants": "2", "rawSize": "3500466", "metadata": { "endcallbackurl": "https:\/\/dev.konn3ct.net\/leftsession", "isBreakout": "false", "meetingId": "23", "meetingName": "Sammy" }, "size": "2074489", "playback": { "format": { "type": "presentation", "url": "https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517", "processingTime": "80959", "length": "4", "size": "2074489", "preview": { "images": { "image": [ "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-1.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-2.png", "https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-3.png" ] } } } }, "data": [] } ]';
                } else {
                    $datas['record'] = \Bigbluebutton::getRecordings([
                        'meetingID' => $r->id,
                    ]);
                }

                $datas['recordings']=json_decode($datas['record'], true);

                return view('user.recording', $datas);

            }else {
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

                $datas['recordings'] = json_decode($datas['record'], true);

                return view('user.recording', $datas);
            }
        } catch (\Exception $e) {

            $datas['recordings'] = [];

            return view('user.recording', $datas);
        }

    }

    public function delete(Request $request){
        $id=$request->id;
        \Bigbluebutton::deleteRecordings([
            'recordID' => $id,
        ]);

        return redirect()->route('recording')->with(['success'=>'Deleted successfully']);
    }
}
