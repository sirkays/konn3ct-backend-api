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

        if ($input['url']==""){
            $num=trim(Auth::user()->name.date('siyhy'));
            $shuffled = str_shuffle($num);
            $sfinal=substr($shuffled, 0, 8);

            $input['url']=$sfinal;
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

        $createMeeting->setDuration(0); //overwrite default configuration
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

       if($bbb->returncode=="SUCCESS"){
           $rm=RoomModel::find($r->id);

           $rm->user_id=Auth::id();
           $rm->bbb_returncode=$bbb->returncode;
           $rm->internalMeetingID=$bbb->internalMeetingID;
           $rm->parentMeetingID=$bbb->parentMeetingID;
           $rm->voiceBridge=$bbb->voiceBridge;
           $rm->createDate=$bbb->createDate;
           $rm->createTime=$bbb->createTime;
           $rm->save();

           return redirect('room')->with('success', 'Room Created Successfully!');
       }else{
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
            return back()
                ->with('error', 'Invalid Room!');
        }

        return redirect()->to(
            Bigbluebutton::join([
                'meetingID' => $i->id,
                'userName' => Auth::user()->name,
                'password' => $i->password_moderator //which user role want to join set password here
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
