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
        $r2=RoomModel::get();
        $datas['recordings']=[];

        if($rc==0){
            return view('admin.recording', $datas);

        }elseif ($rc==1){
            if (App::environment(['local', 'staging'])) {
                $datas['record']='[{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602949708825","endTime":"1602953716866","participants":"6","rawSize":"72464714","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"42442450","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","processingTime":"362454","length":"13","size":"42442450","preview":{"images":{"image":"https:\/\/konn3ct.com\/presentation\/356a192b7913b04c54574d18c28d46e6395428ab-1602949708825\/presentation\/500931cdfdbebc683e886633b3c9524653ffc9f1-1602949930341\/thumbnails\/thumb-1.png"}}}},"data":[]},{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602957298524","endTime":"1602957371601","participants":"1","rawSize":"703164","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"423931","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","processingTime":"4050","length":"0","size":"423931","preview":{"images":{"image":"https:\/\/konn3ct.com\/presentation\/356a192b7913b04c54574d18c28d46e6395428ab-1602957298524\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602957299568\/thumbnails\/thumb-1.png"}}}},"data":[]},{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602938570040","endTime":"1602938887347","participants":"1","rawSize":"1410463","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"2692654","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","processingTime":"50769","length":"4","size":"2692654"}},"data":[]}];';
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
            $er="";
            foreach ($r2 as $r){
                $er=$er."'".$r->id."',";
            }
            $fer="[".$er."]";

            if (App::environment(['local', 'staging'])) {
                $datas['record']='[{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602949708825","endTime":"1602953716866","participants":"6","rawSize":"72464714","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"42442450","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602949708825","processingTime":"362454","length":"13","size":"42442450","preview":{"images":{"image":"https:\/\/konn3ct.com\/presentation\/356a192b7913b04c54574d18c28d46e6395428ab-1602949708825\/presentation\/500931cdfdbebc683e886633b3c9524653ffc9f1-1602949930341\/thumbnails\/thumb-1.png"}}}},"data":[]},{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602957298524","endTime":"1602957371601","participants":"1","rawSize":"703164","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"423931","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602957298524","processingTime":"4050","length":"0","size":"423931","preview":{"images":{"image":"https:\/\/konn3ct.com\/presentation\/356a192b7913b04c54574d18c28d46e6395428ab-1602957298524\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602957299568\/thumbnails\/thumb-1.png"}}}},"data":[]},{"recordID":"356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","meetingID":"1","internalMeetingID":"356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602938570040","endTime":"1602938887347","participants":"1","rawSize":"1410463","metadata":{"isBreakout":"false","meetingId":"1","meetingName":[]},"size":"2692654","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.com\/playback\/presentation\/2.0\/playback.html?meetingId=356a192b7913b04c54574d18c28d46e6395428ab-1602938570040","processingTime":"50769","length":"4","size":"2692654"}},"data":[]}]';
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
}
