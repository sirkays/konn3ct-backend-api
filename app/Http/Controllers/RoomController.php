<?php

namespace App\Http\Controllers;

use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JoisarJignesh\Bigbluebutton\Bigbluebutton;

class RoomController extends Controller
{
    public function create(Request $request){
        $input=$request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'url' => 'nullable|unique:room',
            'dial_number' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Auth::user()->plan==1){
            $r=1;
            $duration=60;
        }elseif(Auth::user()->plan==2){
            $r=5;
            $duration=600;
        }else{
            $r=10000;
            $duration=1440;
        }

        $rc=RoomModel::where("user_id",Auth::id())->count();


        if($rc >= $r){
            return redirect('room')->with('error', 'Maximum room(s) exceeded for your current plan!');
        }


        if ($input['url']==""){
            $num=trim(date('siyh'));
            $shuffled = str_shuffle($num);
            $sfinal=substr($shuffled, 0, 4);

            $input['url']=trim(substr(Auth::user()->name,0, 3).$sfinal);
        }

        $input['password_attendee']="attendee";
        $input['password_moderator']="moderator";
        $input['welcome_message']="";
        $input['logout_url']="";
        $input['max_participants']=0;
        $input['duration']=0;

        $r=RoomModel::create($input);

        $createMeeting = \Bigbluebutton::initCreateMeeting([
            'meetingID' => $r->id,
            'meetingName' => $input['name'],
            'attendeePW' => 'attendee',
            'moderatorPW' => 'moderator',
        ]);

        $createMeeting->setDuration($duration); //overwrite default configuration
        $createMeeting->setLogoutUrl(url('/')); //overwrite default configuration
        $createMeeting->setDialNumber($input['dial_number']); //overwrite default configuration
        $createMeeting->setAllowStartStopRecording(true); //overwrite default configuration
        $createMeeting->setWelcomeMessage("Share this link with people you want in this meeting. <strong>". url('/join/')."/".$input['url']."</strong>"); //overwrite default configuration

//        $meeting->setWelcome('Welecome message for all')
//            ->setModeratorOnlyMessage('Only teacher can see this messsage');

//        $meetingParams->setMaxParticipants
//$meetingParams->setLogoutUrl($
//$meetingParams->setWelcomeMessage(
//        $meetingParams->setDialNumber
//$meetingParams->setBreakout
//$meetingParams->setModeratorOnlyMessage(
//    $meetingParams->setAutoStartRecording
//$meetingParams->setAllowStartStopRecording
// $meetingParams->setWebcamsOnlyForModerator
//$meetingParams->setLogo(
//    $meetingParams->setCopyright
//$meetingParams->setMuteOnStart
// $meetingParams->setLockSettingsDisableCam
//);
//$meetingParams->setLockSettingsDisableMic
//$meetingParams->setLockSettingsDisablePrivateChat
//$meetingParams->setLockSettingsDisablePublicChat
//$meetingParams->setLockSettingsDisableNote
//$meetingParams->setLockSettingsLockedLayout
//$meetingParams->setLockSettingsLockOnJoin
//    $meetingParams->setFreeJoin
       $bbb= \Bigbluebutton::create($createMeeting);
//       $bbb='{"returncode":"SUCCESS","internalMeetingID":"b1d5781111d84f7b3fe45a0852e59758cd7a87e5-1602475017235","parentMeetingID":"bbb-none","createTime":"1602475017235","voiceBridge":"09857","dialNumber":"613-555-1234","createDate":"Mon Oct 12 03:56:57 UTC 2020","hasUserJoined":"false","duration":"100","hasBeenForciblyEnded":"false","messageKey":[],"message":[]}';

        $bba=json_decode($bbb, true);
        $rm=RoomModel::find($r->id);
       if($bba["returncode"]=="SUCCESS"){
           $rm->user_id=Auth::id();
           $rm->bbb_returncode=$bba["returncode"];
           $rm->internalMeetingID=$bba["internalMeetingID"];
           $rm->parentMeetingID=$bba["parentMeetingID"];
           $rm->voiceBridge=$bba["voiceBridge"];
           $rm->createDate=$bba["createDate"];
           $rm->createTime=$bba["createTime"];
           $rm->save();

           return redirect('room')->with('success', 'Room Created Successfully!');
       }else{
           $rm->delete();
           return redirect('room')->with('error', 'Server Error while creating Meeting!');
       }
    }

    public function show(){

        $datas['rooms']=RoomModel::where("user_id", Auth::id())->orderBy('id', 'desc')->get();
        $datas['roomstc']=RoomModel::where("user_id", Auth::id())->count();
        return view('user.dashboard', $datas);
    }

    public function mjoin(Request $request){
        $id=$request->input('id');

        $i=RoomModel::find($id);

        if(!$i){
            return redirect('room')
                ->with('error', 'Invalid Room!');
        }

        $ms=\Bigbluebutton::isMeetingRunning($i->id);

        if($ms==1) {
            return redirect()->to(
                \Bigbluebutton::join([
                    'meetingID' => $i->id,
                    'userName' => Auth::user()->name,
                    'password' => $i->password_moderator //which user role want to join set password here
                ])
            );
        }else{
            $url = \Bigbluebutton::start([
                'meetingID' => $i->id,
                'moderatorPW' => $i->password_moderator, //moderator password set here
                'attendeePW' => $i->password_attendee, //attendee password here
                'userName' => Auth::user()->name,//for join meeting
                //'redirect' => false // only want to create and meeting and get join url then use this parameter
            ]);
            return redirect()->to($url);
        }

    }

    public function ajoin(Request $request){
        $url=$request->input('url');
        $name=$request->input('name');

        $i=RoomModel::where('url',$url)->first();

        if(!$i){
            return redirect('joinsession')
                ->with('error', 'Invalid Room!');
        }

        $ms=\Bigbluebutton::isMeetingRunning($i->id);

        if($ms!=1){
            return redirect('joinsession')
                ->with('error', 'Meeting has not started!');
        }

        if($name==""){
            $name="Konn3ct Guest";
        }

        return redirect()->to(
            \Bigbluebutton::join([
                'meetingID' => $i->id,
                'userName' => $name,
                'password' => $i->password_attendee //which user role want to join set password here
            ])
        );
    }

    public function delete(Request $request){
        $id=$request->get('id');

        $i=RoomModel::find($id);

        if(!$i){
            return back()
                ->with('error', 'Invalid Room!');
        }

        $i->delete();

        return redirect('room')->with('success', 'Room Deleted Successfully!');
    }
}
