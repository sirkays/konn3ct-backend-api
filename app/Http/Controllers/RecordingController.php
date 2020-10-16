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
            $datas['record']=\Bigbluebutton::getRecordings([
                'meetingID' => $r->id,
                //'meetingID' => ['tamku','xyz'], //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);

//            $datas['record']='[{"recordID":"68a04cbb52ace284d6bb314437956c83560a8757-1601405499722","meetingID":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","internalMeetingID":"68a04cbb52ace284d6bb314437956c83560a8757-1601405499722","name":"Stand Up\/Down","isBreakout":"false","published":"true","state":"published","startTime":"1601405499722","endTime":"1601415082346","participants":"5","rawSize":"103219739","metadata":{"name":" Meeting\u00a0","bbb-origin-version":"v2","meetingName":"Stand Up\/Down","meetingId":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","gl-listed":"false","bbb-origin":"Greenlight","isBreakout":"false","bbb-origin-server-name":"konn3ct.ng"},"size":"202698981","playback":{"format":{"type":"presentation","url":"\n      https:\/\/konn3ct.ng\/playback\/presentation\/2.0\/playback.html?meetingId=68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\n    ","processingTime":"11450791","length":"141","size":"202698981","preview":{"images":{"image":["\n            https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601405499753\/thumbnails\/thumb-1.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601405499753\/thumbnails\/thumb-2.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601405499753\/thumbnails\/thumb-3.png\n          "]}}}},"data":[]},{"recordID":"68a04cbb52ace284d6bb314437956c83560a8757-1601366479296","meetingID":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","internalMeetingID":"68a04cbb52ace284d6bb314437956c83560a8757-1601366479296","name":"Stand Up\/Down","isBreakout":"false","published":"true","state":"published","startTime":"1601366479296","endTime":"1601369699429","participants":"3","rawSize":"24761076","metadata":{"bbb-origin-version":"v2","meetingName":"Stand Up\/Down","meetingId":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","gl-listed":"false","bbb-origin":"Greenlight","isBreakout":"false","bbb-origin-server-name":"konn3ct.ng"},"size":"21813282","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.ng\/playback\/presentation\/2.0\/playback.html?meetingId=68a04cbb52ace284d6bb314437956c83560a8757-1601366479296","processingTime":"1052231","length":"50","size":"21813282","preview":{"images":{"image":["https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601366479296\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601366479345\/thumbnails\/thumb-1.png","https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601366479296\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601366479345\/thumbnails\/thumb-2.png","https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601366479296\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601366479345\/thumbnails\/thumb-3.png"]}}}},"data":[]},{"recordID":"1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356","meetingID":"3ltfyejz4zmzlgi2mq0vphkbsfnsu1z3lfq0pvyn","internalMeetingID":"1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356","name":"Home Room","isBreakout":"false","published":"true","state":"published","startTime":"1601455871356","endTime":"1601463487321","participants":"9","rawSize":"88355563","metadata":{"name":"Meeting with Satya","bbb-origin-version":"v2","meetingName":"Home Room","meetingId":"3ltfyejz4zmzlgi2mq0vphkbsfnsu1z3lfq0pvyn","gl-listed":"false","bbb-origin":"Greenlight","isBreakout":"false","bbb-origin-server-name":"konn3ct.ng"},"size":"97298323","playback":{"format":{"type":"presentation","url":"\n      https:\/\/konn3ct.ng\/playback\/presentation\/2.0\/playback.html?meetingId=1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\n    ","processingTime":"4881231","length":"70","size":"97298323","preview":{"images":{"image":["\n            https:\/\/konn3ct.ng\/presentation\/1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601455871380\/thumbnails\/thumb-1.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601455871380\/thumbnails\/thumb-2.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601455871380\/thumbnails\/thumb-3.png\n          "]}}}},"data":[]}]';

            $datas['recordings']=json_decode($datas['record'], true);

            return view('user.recording', $datas);

        }else{
            $er="";
            foreach ($r2 as $r){
                $er=$er."'".$r->id."',";
            }
            $fer="[".$er."]";

            $datas['record']=\Bigbluebutton::getRecordings([
//                'meetingID' => $r->id,
                'meetingID' => $fer, //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);

//            $datas['record']='[{"recordID":"68a04cbb52ace284d6bb314437956c83560a8757-1601405499722","meetingID":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","internalMeetingID":"68a04cbb52ace284d6bb314437956c83560a8757-1601405499722","name":"Stand Up\/Down","isBreakout":"false","published":"true","state":"published","startTime":"1601405499722","endTime":"1601415082346","participants":"5","rawSize":"103219739","metadata":{"name":" Meeting\u00a0","bbb-origin-version":"v2","meetingName":"Stand Up\/Down","meetingId":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","gl-listed":"false","bbb-origin":"Greenlight","isBreakout":"false","bbb-origin-server-name":"konn3ct.ng"},"size":"202698981","playback":{"format":{"type":"presentation","url":"\n      https:\/\/konn3ct.ng\/playback\/presentation\/2.0\/playback.html?meetingId=68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\n    ","processingTime":"11450791","length":"141","size":"202698981","preview":{"images":{"image":["\n            https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601405499753\/thumbnails\/thumb-1.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601405499753\/thumbnails\/thumb-2.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601405499722\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601405499753\/thumbnails\/thumb-3.png\n          "]}}}},"data":[]},{"recordID":"68a04cbb52ace284d6bb314437956c83560a8757-1601366479296","meetingID":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","internalMeetingID":"68a04cbb52ace284d6bb314437956c83560a8757-1601366479296","name":"Stand Up\/Down","isBreakout":"false","published":"true","state":"published","startTime":"1601366479296","endTime":"1601369699429","participants":"3","rawSize":"24761076","metadata":{"bbb-origin-version":"v2","meetingName":"Stand Up\/Down","meetingId":"8oyvvdqu6b28roeckv6wx8nptminbqrup1grccw8","gl-listed":"false","bbb-origin":"Greenlight","isBreakout":"false","bbb-origin-server-name":"konn3ct.ng"},"size":"21813282","playback":{"format":{"type":"presentation","url":"https:\/\/konn3ct.ng\/playback\/presentation\/2.0\/playback.html?meetingId=68a04cbb52ace284d6bb314437956c83560a8757-1601366479296","processingTime":"1052231","length":"50","size":"21813282","preview":{"images":{"image":["https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601366479296\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601366479345\/thumbnails\/thumb-1.png","https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601366479296\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601366479345\/thumbnails\/thumb-2.png","https:\/\/konn3ct.ng\/presentation\/68a04cbb52ace284d6bb314437956c83560a8757-1601366479296\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601366479345\/thumbnails\/thumb-3.png"]}}}},"data":[]},{"recordID":"1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356","meetingID":"3ltfyejz4zmzlgi2mq0vphkbsfnsu1z3lfq0pvyn","internalMeetingID":"1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356","name":"Home Room","isBreakout":"false","published":"true","state":"published","startTime":"1601455871356","endTime":"1601463487321","participants":"9","rawSize":"88355563","metadata":{"name":"Meeting with Satya","bbb-origin-version":"v2","meetingName":"Home Room","meetingId":"3ltfyejz4zmzlgi2mq0vphkbsfnsu1z3lfq0pvyn","gl-listed":"false","bbb-origin":"Greenlight","isBreakout":"false","bbb-origin-server-name":"konn3ct.ng"},"size":"97298323","playback":{"format":{"type":"presentation","url":"\n      https:\/\/konn3ct.ng\/playback\/presentation\/2.0\/playback.html?meetingId=1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\n    ","processingTime":"4881231","length":"70","size":"97298323","preview":{"images":{"image":["\n            https:\/\/konn3ct.ng\/presentation\/1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601455871380\/thumbnails\/thumb-1.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601455871380\/thumbnails\/thumb-2.png\n          ","\n            https:\/\/konn3ct.ng\/presentation\/1cccc1d235ac0b7880268e0b1401871593eef1ff-1601455871356\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1601455871380\/thumbnails\/thumb-3.png\n          "]}}}},"data":[]}]';

            $datas['recordings']=json_decode($datas['record'], true);

            return view('user.recording', $datas);
        }


    }
}
