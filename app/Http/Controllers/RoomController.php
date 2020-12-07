<?php

namespace App\Http\Controllers;

use App\Models\MeetingsModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        $plan=PlanModel::where("id", Auth::user()->plan)->first();
        $r=$plan->rooms;
        $duration=$plan->duration;
        $max_user=$plan->participant;

        $rc=RoomModel::where("user_id",Auth::id())->count();

        if($rc >= $r){
            return redirect('room')->with('error', 'Maximum room(s) exceeded for your current plan!');
        }


        if ($input['url']==""){
            $num=trim(date('siyh'));
            $shuffled = str_shuffle($num);
            $sfinal=substr($shuffled, 0, 4);

            $input['url']=trim(substr(Auth::user()->lastname,0, 3).$sfinal);
        }

        $input['welcome_message']="";
        $input['logout_url']=url('/leftsession');
        $input['max_participants']=$max_user;
        $input['duration']=$duration;

        if($input['access_code']=="") {
            if (isset($input['aujam'])) {
                $input['password_attendee'] = "moderator";
                $input['password_moderator'] = "moderator";
            } else {
                $input['password_attendee'] = "attendee";
                $input['password_moderator'] = "moderator";
            }
        }else{
            if (isset($input['aujam'])) {
                $input['password_attendee'] = $input['access_code'];
                $input['password_moderator'] = $input['access_code'];
            } else {
                $input['password_attendee'] = $input['access_code'];
                $input['password_moderator'] = "moderator";
            }
        }

        $r=RoomModel::create($input);

        $createMeeting = \Bigbluebutton::initCreateMeeting([
            'meetingID' => $r->id,
            'meetingName' => $input['name'],
            'attendeePW' => 'attendee',
            'moderatorPW' => 'moderator',
            'endCallbackUrl'  => url('/leftsession'),
            'logoutUrl' => url('/leftsession'),
        ]);

        $createMeeting->setDuration($duration); //overwrite default configuration
//        $createMeeting->setLogoutUrl(url('/leftsession')); //overwrite default configuration
        if($plan->dialin){
            $createMeeting->setDialNumber($input['dial_number']); //overwrite default configuration
        }
        if($plan->recording){
            $createMeeting->setRecord(true); //overwrite default configuration
            $createMeeting->setAllowStartStopRecording(true); //overwrite default configuration
        }else{
            $createMeeting->setRecord(false); //overwrite default configuration
            $createMeeting->setAllowStartStopRecording(false); //overwrite default configuration
        }
        $createMeeting->setMaxParticipants($max_user); //overwrite default configuration
        $createMeeting->setWelcomeMessage('Welcome to <b>konn3ct</b>!<br><br>For more help on using konn3ct, contact us via support@konn3ct.com.<br><br>No internet? Ask participants to join this meeting by phone & dial:%%DIALNUM%%<br>Then enter %%CONFNUM%% as the Room PIN number.'); //overwrite default configuration
//        $createMeeting->setWelcomeMessage("Share this link with people you want in this meeting. <strong>". url('/join/')."/".$input['url']."</strong>"); //overwrite default configuration

        if(isset($input['muj'])){
            $createMeeting->setMuteOnStart(true); //overwrite default configuration
        }

        if(isset($input['dpuc'])){
            $createMeeting->setLockSettingsDisablePublicChat(true); //overwrite default configuration
        }

        if(isset($input['dprc'])){
            $createMeeting->setLockSettingsDisablePrivateChat(true); //overwrite default configuration
        }

        if(isset($input['ewma'])){
            $createMeeting->setLockSettingsDisableCam(true); //overwrite default configuration
        }

        if(isset($input['dum'])){
            $createMeeting->setLockSettingsDisableMic(true); //overwrite default configuration
        }

        if(isset($input['dsn'])){
            $createMeeting->setLockSettingsDisableNote(true); //overwrite default configuration
        }


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
        $plan=PlanModel::where("id", Auth::user()->plan)->first();
        $r=$plan->rooms;

        $datas['rooms']=RoomModel::where("user_id", Auth::id())->orderBy('id', 'asc')->limit($r)->get();
        $datas['roomstc']=RoomModel::where("user_id", Auth::id())->count();
        if($datas['roomstc']>$r){
            $datas['roomstc']=$r;
        }
        $datas['plan']=PlanModel::where("id", Auth::user()->plan)->first();
        $datas['active']=0;

        if (!App::environment(['local', 'staging'])) {
            foreach ($datas['rooms'] as $i) {
                $ms = \Bigbluebutton::isMeetingRunning($i->id);
                if ($ms) {
                    $datas['active']++;
                }
            }
        }

        if($datas['active']>$r){
            $datas['active']=$r;
        }

        return view('user.dashboard', $datas);
    }

    public function mjoin(Request $request){
        $id=$request->input('id');

        $i=RoomModel::find($id);

        if(!$i){
            return back()
                ->with('error', 'Invalid Room!');
        }

        $ms=\Bigbluebutton::isMeetingRunning($i->id);

        if($ms==1) {
            return redirect()->to(
                \Bigbluebutton::join([
                    'meetingID' => $i->id,
                    'userName' => Auth::user()->lastname ." " .Auth::user()->firstname,
                    'password' => $i->password_moderator //which user role want to join set password here
                ])
            );
        }else{
            $plan=PlanModel::where("id", Auth::user()->plan)->first();
            if($plan->recording){
                $record=true; //overwrite default configuration
            }else{
                $record=false; //overwrite default configuration
            }

            $duration=$plan->duration;
            $max_user=$plan->participant;

            if($i->muj){
                $muj=true;
            }else{
                $muj=false;
            }

            if($i->dpuc){
                $dpuc=true;
            }else{
                $dpuc=false;
            }

            if($i->dprc){
                $dprc=true;
            }else{
                $dprc=false;
            }

            if($i->ewma){
                $ewma=true;
            }else{
                $ewma=false;
            }

            if($i->dum){
                $dum=true;
            }else{
                $dum=false;
            }

            if($i->dsn){
                $dsn=true;
            }else{
                $dsn=false;
            }

            $mdata['meeting_id']=$i->id;
            $mdata['name']=Auth::user()->lastname ." " .Auth::user()->firstname;
            $mdata['email']=Auth::user()->email;
            $mdata['password_attendee']=$i->password_attendee;
            $mdata['status']="start meeting";
            MeetingsModel::create($mdata);

            $url = \Bigbluebutton::start([
                'meetingID' => $i->id,
                'moderatorPW' => $i->password_moderator, //moderator password set here
                'attendeePW' => $i->password_attendee, //attendee password here
                'meetingName' => $i->name,
                'userName' => Auth::user()->lastname ." " .Auth::user()->firstname,//for join meeting
                'endCallbackUrl'  => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage'=> 'Welcome to <b>konn3ct</b>!<br><br>For more help on using konn3ct, contact us via support@konn3ct.com.<br><br>No internet? Ask participants to join this meeting by phone & dial:%%DIALNUM%%<br>Then enter %%CONFNUM%% as the Room PIN number.',
//                'welcomeMessage'=> "Share this link with people you want in this meeting. <strong>". url('/join/')."/".$i->url."</strong>",
                'allowStartStopRecording'=> $record,
                'record'=>$record,
                'duration' =>$duration,
                'maxParticipants' =>$max_user,
                'muteOnStart' => $muj,
                'lockSettingsDisablePublicChat' => $dpuc,
                'lockSettingsDisablePrivateChat' => $dprc,
                'lockSettingsDisableCam' => $ewma,
                'lockSettingsDisableMic' => $dum,
                'lockSettingsDisableNote'=> $dsn
                //'redirect' => false // only want to create and meeting and get join url then use this parameter
            ]);
            return redirect()->to($url);
        }

    }

    public function ajoin(Request $request){
        $url=$request->input('url');
        $name=$request->input('name');
        $email=$request->input('email');

        $i=RoomModel::where('url',$url)->first();

        if(!$i){
            return back()
                ->with('error', 'Invalid Room!');
        }

        $ms=\Bigbluebutton::isMeetingRunning($i->id);

        $mdata['meeting_id']=$i->id;
        $mdata['name']=$name;
        $mdata['email']=$email;
        $mdata['password_attendee']=$i->password_attendee;

        if($ms!=1){
            $mdata['status']="meeting not started";

            MeetingsModel::create($mdata);

            return back()
                ->with('error', 'Meeting has not started!');
        }

        if($name==""){
            $name="Konn3ct Guest";
        }

        $mdata['status']="joined";
        MeetingsModel::create($mdata);

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

    public function invite(Request $request){
        $input=$request->all();

        if ($input['guest']==""){
            return back()->with('error', 'Guest emails can not be empty');
        }

        // use of explode
        $str_arr = explode (",", $input['guest']);

        foreach ($str_arr as $arr) {

            $GLOBALS['recipient'] = trim($arr);

            $data = array('ihost' => $input['hostname'], 'ilink' => $input['roomlink'], 'idate' => $input['date'], 'itime' => $input['time'], 'iroom' => $input['roomname']);
            Mail::send('mail.invite', $data, function ($message) {
                $message->to($GLOBALS['recipient'])->subject('Konn3ct Invite');
            });
        }

        return redirect('room')->with('success', 'Invite Sent Successfully!');
    }
}
