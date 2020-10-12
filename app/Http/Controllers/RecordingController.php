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
        $r2=RoomModel::where('user_id', Auth::id())->pluck('id');
        $datas['recordings']=[];

        if($rc==0){
            return view('user.recording', $datas);

        }elseif ($rc==1){
            $datas['recordings']=\Bigbluebutton::getRecordings([
                'meetingID' => $r->id,
                //'meetingID' => ['tamku','xyz'], //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);

            return view('user.recording', $datas);

        }else{
            $datas['recordings']=\Bigbluebutton::getRecordings([
//                'meetingID' => $r->id,
                'meetingID' => $r2, //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);

            return view('user.recording', $datas);
        }


    }
}
