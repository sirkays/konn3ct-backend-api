<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class RecordingsController extends Controller
{
    public function show(){

        $rc=RoomModel::count();
        $r=RoomModel::first();
        $r2=RoomModel::latest()->first();
        $datas['recordings']=[];
        $datas['i']=1;

        if($rc==0){
            return view('admin.recording', $datas);

        }elseif ($rc==1){
            if (App::environment(['local', 'staging'])) {
                $datas['record']='[{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604321641383","endTime":"1604322318372","participants":"2","rawSize":"4549560","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"4158906","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","processingTime":"178453","length":"10","size":"4158906","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png"]}}}},"data":[]},{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604320993517","endTime":"1604321453617","participants":"2","rawSize":"3500466","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"2074489","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","processingTime":"80959","length":"4","size":"2074489","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-3.png"]}}}},"data":[]}]';
            }else {
                $datas['record'] = \Bigbluebutton::getRecordings([
                    'meetingID' => $r->id,
                    //'meetingID' => ['tamku','xyz'], //pass as array if get multiple recordings
                    //'recordID' => 'a3f1s',
                    //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                    // 'state' => 'any' // It can be a set of states separate by commas
                ]);
            }

            $datas['recordings']=json_decode($datas['record'], true);

            return view('admin.recording', $datas);

        }else{
            $r=$r2->id;
            $er="1";
            for ($i=2; $i <= $r; $i++){
                $er=$er.",".$i;
            }
            $fer="[".$er."]";

            if (App::environment(['local', 'staging'])) {
                $datas['record']='[{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604321641383","endTime":"1604322318372","participants":"2","rawSize":"4549560","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"4158906","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","processingTime":"178453","length":"10","size":"4158906","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png"]}}}},"data":[]},{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604320993517","endTime":"1604321453617","participants":"2","rawSize":"3500466","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"2074489","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","processingTime":"80959","length":"4","size":"2074489","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-3.png"]}}}},"data":[]}]';
            }else {

                $datas['record'] = \Bigbluebutton::getRecordings([
//                'meetingID' => $r->id,
                    'meetingID' => $fer, //pass as array if get multiple recordings
                    //'recordID' => 'a3f1s',
                    //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                    // 'state' => 'any' // It can be a set of states separate by commas
                ]);
            }

            $datas['recordings']=json_decode($datas['record'], true);


            return view('admin.recording', $datas);
        }
    }

    public function delete(Request $request){
        $id=$request->id;
        \Bigbluebutton::deleteRecordings([
            'recordID' => $id,
        ]);

        return redirect()->route('admin.recordings.delete')->with(['success'=>'Deleted successfully']);
    }
}
