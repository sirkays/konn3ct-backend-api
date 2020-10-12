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
            $num=Auth::user()->name.date('siyhy');
            $shuffled = str_shuffle($num);
            $sfinal=substr($shuffled, 0, 8);

            $input['url']=$sfinal;
        }

        $input['user_id']=Auth::id();
        $input['password_attendee']="";
        $input['password_moderator']="";
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

        $createMeeting->setDuration(100); //overwrite default configuration
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
       return \Bigbluebutton::create($createMeeting);

//        return redirect('room')->with('success', 'Room Created Successfully!');
    }

    public function show(){

        $datas['rooms']=RoomModel::where("user_id", Auth::id())->orderBy('id', 'desc')->get();
        $datas['roomstc']=RoomModel::where("user_id", Auth::id())->count();
        return view('user.dashboard', $datas);
    }
}
