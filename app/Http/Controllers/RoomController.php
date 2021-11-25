<?php

namespace App\Http\Controllers;

use App\Jobs\CreateBGAccountJob;
use App\Jobs\Konn3ctChatCreateGroupJob;
use App\Jobs\Konn3ctChatGroupInviteJob;
use App\Models\MeetingsModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $plan = PlanModel::where("id", Auth::user()->plan)->first();
        $r = $plan->rooms + Auth::user()->room_bundles;
        $duration = $plan->duration;
        $max_user=$plan->participant;

        $rc=RoomModel::where("user_id",Auth::id())->count();

//        echo $r;
//        echo $plan->rooms;
//        echo "|||" .  Auth::user()->room_bundles ."|||||";
//        echo intval(($plan->rooms * 1)) + intval((Auth::user()->room_bundles * 1));
//        return;
        if($rc >= $r){
            return redirect()->route('rooms')->with('error', 'Maximum room(s) exceeded for your current plan!');
        }

        if ($input['url']==""){
            $num=trim(date('siyh'));
            $shuffled = str_shuffle(substr(Auth::user()->firstname,0, 2).substr(str_shuffle($num),0, 4));
            $sfinal=substr($shuffled, 0, 6);

            if (Auth::user()->lastname==""){
                $input['url']=trim(substr(Auth::user()->firstname,0, 3).$sfinal);
            }else{
                $input['url']=trim(substr(Auth::user()->lastname,0, 3).$sfinal);
            }

        }

        $input['welcome_message']="";
        $input['logout_url']=url('/leftsession');
        $input['max_participants']=$max_user;
        $input['duration']=$duration;
        $input['url']=preg_replace('/\s+/', '', $input['url']);

        if($input['access_code']=="") {
            $input['password_attendee'] = "attendee";
            $input['password_moderator'] = "moderator";
        }else{
            $input['password_attendee'] = $input['access_code'];
            $input['password_moderator'] = "moderator";
        }

        $r=RoomModel::create($input);

        $createMeeting = \Bigbluebutton::initCreateMeeting([
            'meetingID' => "0$r->id",
            'meetingName' => $input['name'],
            'attendeePW' => $input['password_attendee'],
            'moderatorPW' => $input['password_moderator'],
            'endCallbackUrl' => url('/leftsession'),
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
        $createMeeting->setWelcomeMessage('Welcome to <span style="color: #008b8b;"> konn3ct!</span><br><br>Host: '.Auth::user()->firstname.'<br>Meeting Link: <a href="'. url("/join/").'/'.$input["url"].'" <span style="color: #008b8b;">'. url("/join/").'/'.$input["url"].'</span></a><br>Dial-in: <span style="color: #008b8b;">%%DIALNUM%%</span> PIN: <span style="color: #008b8b;">%%CONFNUM%%</span>'); //overwrite default configuration
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
        $bbb = \Bigbluebutton::create($createMeeting);
//       $bbb='{"returncode":"SUCCESS","internalMeetingID":"b1d5781111d84f7b3fe45a0852e59758cd7a87e5-1602475017235","parentMeetingID":"bbb-none","createTime":"1602475017235","voiceBridge":"09857","dialNumber":"613-555-1234","createDate":"Mon Oct 12 03:56:57 UTC 2020","hasUserJoined":"false","duration":"100","hasBeenForciblyEnded":"false","messageKey":[],"message":[]}';

        $bba=json_decode($bbb, true);
        $rm=RoomModel::find($r->id);

        if($bba["returncode"]=="SUCCESS") {
            $rm->user_id = Auth::id();
            $rm->bbb_returncode = $bba["returncode"];
            $rm->internalMeetingID = $bba["internalMeetingID"];
            $rm->parentMeetingID = $bba["parentMeetingID"];
            $rm->voiceBridge = $bba["voiceBridge"];
            $rm->createDate = $bba["createDate"];
            $rm->createTime = $bba["createTime"];
            $rm->save();


            $jobi['name'] = $input['name'];
            $jobi['email'] = Auth::user()->email;

            Konn3ctChatCreateGroupJob::dispatch($jobi)->delay(now()->addSeconds(1));

            return redirect()->route('rooms')->with('success', 'Room Created Successfully!');
        }else{
            $rm->delete();
            return redirect()->route('rooms')->with('error', 'Server Error while creating Meeting!');
        }
    }

    public function show(){
        $plan = PlanModel::where("id", Auth::user()->plan)->first();
        $r = $plan->rooms + Auth::user()->room_bundles;

        $datas['rooms']=RoomModel::where("user_id", Auth::id())->orderBy('id', 'asc')->limit($r)->get();
        $datas['roomstc']=RoomModel::where("user_id", Auth::id())->count();
        if($datas['roomstc']>$r){
            $datas['roomstc']=$r;
        }
        $datas['plan']=PlanModel::where("id", Auth::user()->plan)->first();
        $datas['active']=0;

        if (!App::environment(['local', 'staging'])) {
            foreach ($datas['rooms'] as $i) {
                $ms = \Bigbluebutton::isMeetingRunning("0$i->id");
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
        $id = $request->input('id');

        $i = RoomModel::find($id);

        if (!$i) {
            return back()->with('error', 'Invalid Room!');
        }

        $rm_id = "0$i->id";

        $ms = \Bigbluebutton::isMeetingRunning($rm_id);

        if ($ms != 1) {
            $plan = PlanModel::where("id", Auth::user()->plan)->first();

            if ($plan->recording) {
                $record = true;
            } else {
                $record = false;
            }

            $duration = $plan->duration;
            $max_user = $plan->participant;

            if ($i->muj) {
                $muj = true;
            } else {
                $muj = false;
            }

            if ($i->dpuc) {
                $dpuc = true;
            } else {
                $dpuc = false;
            }

            if ($i->dprc) {
                $dprc = true;
            } else {
                $dprc = false;
            }

            if ($i->ewma) {
                $ewma = true;
            } else {
                $ewma = false;
            }

            if ($i->dum) {
                $dum = true;
            } else {
                $dum = false;
            }

            if ($i->dsn) {
                $dsn = true;
            } else {
                $dsn = false;
            }

            if ($i->aujam) {
                $up = "moderator";
            } else {
                $up = $i->password_attendee;
            }

            if ($i->banner != "") {
                $banner = url('/') . "/myroombanner/" . $i->banner;
            } else {
                $banner = "https://konn3ct.com/assets/images/konn3ct_logo.png";
            }

            $mdata['meeting_id'] = "$i->id";
            $mdata['name'] = Auth::user()->lastname . " " . Auth::user()->firstname;
            $mdata['email'] = Auth::user()->email;
            $mdata['password_attendee'] = $up;
            $mdata['status'] = "start meeting";
            $mdata['identifier'] = $i->id . rand();
            MeetingsModel::create($mdata);

            \Bigbluebutton::create([
                'meetingID' => $rm_id,
                'moderatorPW' => $i->password_moderator, //moderator password set here
                'attendeePW' => $up, //attendee password here
                'meetingName' => $i->name,
                'endCallbackUrl' => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage' => 'Welcome to <span style="color: #008b8b;"> konn3ct!</span><br><br>Host: ' . Auth::user()->firstname . '<br>Meeting Link: <a href="' . url("/join/") . '/' . $i->url . '" <span style="color: #008b8b;">' . url("/join/") . '/' . $i->url . '</span></a><br/>Dial-In: <span style="color: #008b8b;">%%DIALNUM%%</span> PIN: <span style="color: #008b8b;">%%CONFNUM%%</span>',
                'allowStartStopRecording' => $record,
                'record' => $record,
                'duration' => $duration,
                'maxParticipants' => $max_user,
                'muteOnStart' => $muj,
                'lockSettingsDisablePublicChat' => $dpuc,
                'lockSettingsDisablePrivateChat' => $dprc,
                'lockSettingsDisableCam' => $ewma,
                'lockSettingsDisableMic' => $dum,
                'lockSettingsDisableNote' => $dsn,
                'logo' => $banner
            ]);
        }


        $u = User::where('email', Auth::user()->email)->first();
        $dp = 'https://konn3ct.com/assets/images/konn3ctIcon.png';

        if ($u->profile_photo_url != "" && $u->profile_photo_url != NULL) {

            $resul = $u->profile_photo_url;
            $findme = 'ui-avatars.com';
            $pos = strpos($resul, $findme);
            // Note our use of ===.  Simply == would not work as expected
            if ($pos === false) {
                $dp = $u->profile_photo_url;
            }
        }

        $url = \Bigbluebutton::join([
            'meetingID' => $rm_id,
            'userName' => Auth::user()->lastname . " " . Auth::user()->firstname,
            'userId' => Auth::user()->email,
            'password' => $i->password_moderator, //which user role want to join set password here
            'avatarUrl' => $dp,
            'customParameters' => [
                'userdata-bbb_auto_join_audio' => 'true',
                'userdata-bbb_enable_video' => 'true',
                'userdata-bbb_listen_only_mode' => 'false',
                'userdata-bbb_force_listen_only' => 'false',
                'userdata-bbb_skip_check_audio' => 'true',
                'userdata-bbb_user_email' => Auth::user()->email
            ],
        ]);

            return redirect()->to($url);
    }

    public function ajoin(Request $request){
        $url=$request->input('url');
        $name=$request->input('name');
        $email=$request->input('email');
        session(['url' => $url]);
        session(['name' => $name]);
        session(['email' => $email]);

        $i=RoomModel::where('url',$url)->orWhere('name',$url)->first();

        if(!$i){
            return back()
                ->with('error', 'Room url or name does not exist, kindly check your input and try again!');
        }

        $u = User::find($i->user_id);

        $ms = \Bigbluebutton::isMeetingRunning("0$i->id");
//        $ms=1;

        $mdata['meeting_id'] = $i->id;
        $mdata['name'] = $name;
        $mdata['email'] = $email;
        $mdata['password_attendee'] = $i->password_attendee;

        session(['room-owner' => $u->email]);

        if ($ms != 1) {
            $mdata['status'] = "meeting not started";

            MeetingsModel::create($mdata);

            $mns['name'] = $name;
            $mns['email'] = $email;
            $mns['url'] = $url;
            $mns['room'] = $i;
            $mns['owner']=$u;

            return view('meeting_notstarted', $mns);
//
//            return back()
//                ->with('error', 'Meeting has not started!');
        }else{
            $mds = \Bigbluebutton::getMeetingInfo([
                'meetingID' => "0$i->id",
                'moderatorPW' => $i->password_moderator //moderator password set here
            ]);

            $data['status']="Currently on";
            $data['meetingname']=$i->name;
            $data['meetinghost']=$u->firstname . " " .$u->lastname;
            $data['dialNumber']=$mds['dialNumber'];
            $data['pin']=$mds['voiceBridge'];
            $data['pcount']=$mds['participantCount'];
            $data['participants']="";
            if($data['pcount']==1){
                $data['participants']=$mds['attendees']['attendee']['fullName'];
            }else {
                foreach ($mds['attendees']['attendee'] as $attend) {
                    $att = $attend['fullName'] . ", ";
                    $data['participants'] .= $att;
                }
            }
            if($i->password_attendee=="attendee"){
                $data['acode']=false;
            }else{
                $data['acode']=true;
            }

            return view('konn3ct_session', $data);
        }
    }

    public function fjoin(Request $request){
        $url=session('url');
        $name=session('name');
        $email=session('email');

        $i=RoomModel::where('url',$url)->orWhere('name',$url)->first();

        if(!$i){
            return redirect('joinsession')
                ->with('error', 'Room url or name does not exist, kindly check your input and try again!');
        }

        if($i->password_attendee != 'attendee'){
            if($i->password_attendee != $request->get('accesscode')) {
                return redirect('joinsession')
                    ->with('error', 'Wrong access code, kindly ask the correct access code from the moderator!');
            }
            $password_attendee = $request->get('accesscode');
        }else{
            $password_attendee = 'attendee';
        }

        if($i->aujam){
            $password_attendee="moderator";
        }

        $ms = \Bigbluebutton::isMeetingRunning("0$i->id");

        $mdata['meeting_id']=$i->id;
        $mdata['name']=$name;
        $mdata['email']=$email;
        $mdata['password_attendee']=$password_attendee;

        if($ms!=1){
            $mdata['status']="meeting not started";

            MeetingsModel::create($mdata);

            return redirect('joinsession')
                ->with('error', 'Not yet started!');
        }

        if($name==""){
            $name="Konn3ct Guest";
        }

        $fm=MeetingsModel::where('meeting_id','=',$i->id)->orderBy('id', 'desc')->first();

        $mdata['identifier']=$fm->identifier;
        $mdata['status']="joined";
        MeetingsModel::create($mdata);

        $u = User::where('email', $email)->first();
        $dp = 'https://konn3ct.com/assets/images/konn3ctIcon.png';

        if ($u) {
            if ($u->profile_photo_url != "" && $u->profile_photo_url != NULL) {

                $resul = $u->profile_photo_url;
                $findme = 'ui-avatars.com';
                $pos = strpos($resul, $findme);
                // Note our use of ===.  Simply == would not work as expected
                if ($pos === false) {
                    $dp = $u->profile_photo_url;
                }
            }
        } else {
            $jobi['name'] = $name;
            $jobi['email'] = $email;

            CreateBGAccountJob::dispatch($jobi)->delay(now()->addSecond());
        }

        $jobi['name'] = $i->name;
        $jobi['email'] = session('room-owner');
        $jobi['invitee'] = $email;
        $jobi['inviteeName'] = $name;

        Konn3ctChatGroupInviteJob::dispatch($jobi)->delay(now()->addSeconds(35));

        return redirect()->to(
            \Bigbluebutton::join([
                'meetingID' => "0$i->id",
                'userName' => $name,
                'userId' => $email,
                'password' => $password_attendee, //which user role want to join set password here
                'avatarUrl' => $dp,
                'customParameters' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_enable_video' => 'true',
                    'userdata-bbb_listen_only_mode' => 'false',
                    'userdata-bbb_force_listen_only' => 'false',
                    'userdata-bbb_skip_check_audio' => 'true',
                    'userdata-bbb_user_email' => $email
                ],
            ])
        );

    }

    public function delete(Request $request){
        $id=$request->get('id');

        $i=RoomModel::find($id);

        if (!$i) {
            return back()
                ->with('error', 'Invalid Room!');
        }

        $i->delete();

        return redirect()->route('rooms')->with('success', 'Room Deleted Successfully!');
    }

    public function accesscode(Request $request)
    {
        $input = $request->all();

        $r=RoomModel::find($input['id']);

        if ($input['type']=="manual" && $input['accesscode']==""){
            return back()->with('error', 'Access code can not be empty');
        }else{
            $r->password_attendee=$input['accesscode'];
            $r->save();
        }

        if($input['type']!="manual"){
            $code=rand(11111,9999999999);
            $r->password_attendee=$code;
            $r->save();
        }

        return redirect()->route('rooms')->with('success', 'Access code changed Successfully!');
    }

    public function limituser(Request $request){
        $input=$request->all();

        $r=RoomModel::find($input['id']);

        $r->max_participants=$input['users'];
        $r->save();

        return redirect()->route('rooms')->with('success', 'User Limit changed Successfully!');
    }

    public function roomstatus($url){
        $i = RoomModel::where('url', $url)->first();
        $ms = \Bigbluebutton::isMeetingRunning("0$i->id");
//        $ms=0;
        if($ms!=1){
            return response()->json(['status'=>0, 'message'=>'Meeting not started']);
        }

        return response()->json(['status'=>1, 'message'=>'Meeting not started']);
    }

    public function bannerupload(Request $request){

        if (!$request->hasFile('banner')) {
            return back()->with('error', 'Upload file not found');
        }

        $file = $request->file('banner');
        if (!$file->isValid()) {
            return back()->with('error', 'Invalid file upload');
        }

        if ($file->getClientOriginalExtension() != "png" && $file->getClientOriginalExtension() != "jpg" && $file->getClientOriginalExtension() != "jpeg") {
            return back()->with('error', 'Kindly upload a png/jpg/jpeg file');
        }

        $fName = rand().".jpg";

        $path = storage_path('roombanner/');
        $file->move($path, $fName);

        echo "Uploaded successfully";

        $i = RoomModel::find($request->id);
        $i->banner = $fName;
        $i->save();

        return back()->with('success', 'Banner has been uploaded successfully');
    }

    public function attendance($id)
    {
        $room = RoomModel::find($id);
        if ($room->user_id != Auth::id()) {
            abort(404);
        }
        $datas['meetings'] = MeetingsModel::where([["meeting_id", $id], ["status", "=", "start meeting"]])->orderBy('id', 'desc')->get();
        $datas['i'] = 1;
        return view('user.attendance', $datas);
    }

    public function participants($id)
    {
        $datas['meetings'] = MeetingsModel::where([["identifier", "=", $id]])->orderBy('id', 'desc')->get();
        $datas['i'] = 1;
        return view('user.attendance_participants', $datas);
    }

}
