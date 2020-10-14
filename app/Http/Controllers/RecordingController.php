<?php

namespace App\Http\Controllers;

use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordingController extends Controller
{
    public function show(){

        $rc=RoomModel::where('user_id', Auth::id())->count();
        $r=RoomModel::where('user_id', Auth::id())->select('id')->first();
        $r2=RoomModel::where('user_id', Auth::id())->select('id')->get();
        $datas['recordings']=[];

        if($rc==0){
            return view('user.recording', $datas);

        }elseif ($rc==1){
            /*$datas['recordings']=\Bigbluebutton::getRecordings([
                'meetingID' => $r->id,
                //'meetingID' => ['tamku','xyz'], //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);*/

//            $datas['recordings']='["recordID" => "b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143", "meetingID" => "19", "internalMeetingID" => "b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143", "name" => "RChurch", "isBreakout" => "false", "published" => "true", "state" => "published", "startTime" => "1602593914143", "endTime" => "1602594231915", "participants" => "1", "rawSize" => "3638439", "metadata" => [ "isBreakout" => "false", "meetingId" => "19", "meetingName" => "RChurch", ], "size" => "2062569", "playback" => [ "format" => [ "type" => "presentation", "url" => "https://konn3ct.ng/playback/presentation/2.0/playback.html?meetingId=b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143", "processingTime" => "70736", "length" => "3", "size" => "2062569", "preview" => [ "images" => [ "image" => [ "https://konn3ct.ng/presentation/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602593914165/thumbnails/thumb-1.png", "https://konn3ct.ng/presentation/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602593914165/thumbnails/thumb-2.png", "https://konn3ct.ng/presentation/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602593914165/thumbnails/thumb-3.png", ], ], ], ], ], "data" => [], ]';
            $datas['recordings']='{"recordID":"91032ad7bbcb6cf72875e8e8207dcfba80173f7c-1602674899727","meetingID":"20","internalMeetingID":"91032ad7bbcb6cf72875e8e8207dcfba80173f7c-1602674899727","name":[],"isBreakout":"false","published":"true","state":"published","startTime":"1602674899727","endTime":"1602674977476","participants":"1","rawSize":"2748985","metadata":{"isBreakout":"false","meetingId":"20","meetingName":[]},"size":"800832","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.ng\/playback\/presentation\/2.0\/playback.html?meetingId=91032ad7bbcb6cf72875e8e8207dcfba80173f7c-1602674899727","processingTime":"14015","length":"0","size":"800832","preview":{"images":{"image":["https:\/\/konn3ct.ng\/presentation\/91032ad7bbcb6cf72875e8e8207dcfba80173f7c-1602674899727\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602674899968\/thumbnails\/thumb-1.png","https:\/\/konn3ct.ng\/presentation\/91032ad7bbcb6cf72875e8e8207dcfba80173f7c-1602674899727\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602674899968\/thumbnails\/thumb-2.png","https:\/\/konn3ct.ng\/presentation\/91032ad7bbcb6cf72875e8e8207dcfba80173f7c-1602674899727\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602674899968\/thumbnails\/thumb-3.png"]}}}},"data":[]}';

            return view('user.recording', $datas);

        }else{
            $er="";
            foreach ($r2 as $r){
                $er=$er."'".$r->id."',";
            }
            $fer="[".$er."]";

            /*$datas['recordings']=\Bigbluebutton::getRecordings([
//                'meetingID' => $r->id,
                'meetingID' => $fer, //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);*/

            $datas['recordings']='["recordID" => "b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143", "meetingID" => "19", "internalMeetingID" => "b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143", "name" => "RChurch", "isBreakout" => "false", "published" => "true", "state" => "published", "startTime" => "1602593914143", "endTime" => "1602594231915", "participants" => "1", "rawSize" => "3638439", "metadata" => [ "isBreakout" => "false", "meetingId" => "19", "meetingName" => "RChurch", ], "size" => "2062569", "playback" => [ "format" => [ "type" => "presentation", "url" => "https://konn3ct.ng/playback/presentation/2.0/playback.html?meetingId=b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143", "processingTime" => "70736", "length" => "3", "size" => "2062569", "preview" => [ "images" => [ "image" => [ "https://konn3ct.ng/presentation/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602593914165/thumbnails/thumb-1.png", "https://konn3ct.ng/presentation/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602593914165/thumbnails/thumb-2.png", "https://konn3ct.ng/presentation/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f-1602593914143/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1602593914165/thumbnails/thumb-3.png", ], ], ], ], ], "data" => [], ]';

            return view('user.recording', $datas);
        }


    }
}
