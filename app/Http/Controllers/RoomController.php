<?php

namespace App\Http\Controllers;

use App\Mail\InviteMail;
use App\Models\MeetingsModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
            'meetingID' => $r->id,
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

        $ms = \Bigbluebutton::isMeetingRunning($i->id);

        if($ms==1) {
            return redirect()->to(
                \Bigbluebutton::join([
                    'meetingID' => $i->id,
                    'userName' => Auth::user()->lastname . " " . Auth::user()->firstname,
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

            if($i->aujam){
                $up="moderator";
            }else{
                $up=$i->password_attendee;
            }

            if($i->banner!=""){
                $banner= url('/') . "/roombanner/".$i->banner;
            }else{
                $banner="https://konn3ct.com/assets/images/konn3ct_logo.png";
            }

            $mdata['meeting_id']=$i->id;
            $mdata['name']=Auth::user()->lastname ." " .Auth::user()->firstname;
            $mdata['email']=Auth::user()->email;
            $mdata['password_attendee']=$up;
            $mdata['status']="start meeting";
            $mdata['identifier']=$i->id.rand();
            MeetingsModel::create($mdata);

            $url = \Bigbluebutton::start([
                'meetingID' => $i->id,
                'moderatorPW' => $i->password_moderator, //moderator password set here
                'attendeePW' => $up, //attendee password here
                'meetingName' => $i->name,
                'userName' => Auth::user()->lastname . " " . Auth::user()->firstname,//for join meeting
                'endCallbackUrl' => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage' => 'Welcome to <span style="color: #008b8b;"> konn3ct!</span><br><br>Host: ' . Auth::user()->firstname . '<br>Meeting Link: <a href="' . url("/join/") . '/' . $i->url . '" <span style="color: #008b8b;">' . url("/join/") . '/' . $i->url . '</span></a><br/>Dial-In: <span style="color: #008b8b;">%%DIALNUM%%</span> PIN: <span style="color: #008b8b;">%%CONFNUM%%</span>',
//                'welcomeMessage'=> "Share this link with people you want in this meeting. <strong>". url('/join/')."/".$i->url."</strong>",
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
                'logo' => $banner,
                'meta' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_listen_only_mode' => 'true',
                    'userdata-userdata-bbb_force_listen_only' => 'true',
                    'userdata-bbb_skip_check_audio' => 'true'
                ]
                //'redirect' => false // only want to create and meeting and get join url then use this parameter
            ]);
//            return redirect()->to($url);
            return $url;
        }

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

        $u=User::find($i->user_id);

        $ms = \Bigbluebutton::isMeetingRunning($i->id);
//        $ms=1;

        $mdata['meeting_id']=$i->id;
        $mdata['name']=$name;
        $mdata['email']=$email;
        $mdata['password_attendee']=$i->password_attendee;

        if($ms!=1){
            $mdata['status']="meeting not started";

            MeetingsModel::create($mdata);

            $mns['name']=$name;
            $mns['email']=$email;
            $mns['url']=$url;
            $mns['room']=$i;
            $mns['owner']=$u;

            return view('meeting_notstarted', $mns);
//
//            return back()
//                ->with('error', 'Meeting has not started!');
        }else{
            $mds = \Bigbluebutton::getMeetingInfo([
                'meetingID' => $i->id,
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

        $ms = \Bigbluebutton::isMeetingRunning($i->id);

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

//        return redirect()->to(
//            \Bigbluebutton::join([
//                'meetingID' => $i->id,
//                'userName' => $name,
//                'password' => $password_attendee, //which user role want to join set password here
//                'avatarUrl' => 'https://dev.konn3ct.net/assets/images/konn3ctIcon.png',
//                'clientUrl' => 'https://dev.konn3ct.net/assets/images/konn3ctIcon.png',
//                'customParameters' => [
//                    ['userdata-bbb_auto_join_audio' => 'true'],
//                    ['userdata-bbb_listen_only_mode' => 'true'],
//                    ['userdata-userdata-bbb_force_listen_only' => 'true'],
//                    ['userdata-bbb_skip_check_audio' => 'true']
//                ]
//            ])
//        );

           return \Bigbluebutton::join([
                'meetingID' => $i->id,
                'userName' => $name,
                'password' => $password_attendee, //which user role want to join set password here
                'avatarUrl' => 'https://dev.konn3ct.net/assets/images/konn3ctIcon.png',
               'customParameters' => [
                   'userdata-bbb_auto_join_audio' => true,
                   'userdata-bbb_listen_only_mode' => true,
                   'userdata-userdata-bbb_force_listen_only' => true,
                   'userdata-bbb_skip_check_audio' => true
               ],
            ]);

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

        #TODO: save all emails sent to one table for campaigns later
        $input['guest'].=",".Auth::user()->email;

        // use of explode
        $str_arr = explode (",", $input['guest']);

        foreach ($str_arr as $arr) {

            $GLOBALS['recipient'] = trim($arr);

            try {
                if ($GLOBALS['recipient'] != "") {

                    $data['ihost']=$input['hostname'];

                    $data['ilink']=$input['roomlink'];

                    $data['iaccesscode']=$input['accesscode'];

                    $data['imtitle']=$input['title'];

                    $data['idate']=$input['date'];

                    $data['itime'] = $input['time'];

                    $data['iroom'] = $input['roomname'];

                    $data['itimezone'] = $input['timezone'];

                    $data['iadditional'] = $input['additional'] ?? '';

                    Mail::to($GLOBALS['recipient'])->send(new InviteMail($data));
                }
            } catch (Exception $e) {
                echo "error when sending email";
            }
        }

        return redirect('room')->with('success', 'Invite Sent Successfully!');
    }

    public function accesscode(Request $request){
        $input=$request->all();

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

        return redirect('room')->with('success', 'Access code changed Successfully!');
    }

    public function limituser(Request $request){
        $input=$request->all();

        $r=RoomModel::find($input['id']);

        $r->max_participants=$input['users'];
        $r->save();

        return redirect('room')->with('success', 'User Limit changed Successfully!');
    }

    public function roomstatus($url){
        $i = RoomModel::where('url', $url)->first();
        $ms = \Bigbluebutton::isMeetingRunning($i->id);
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

        $i=RoomModel::find($request->id);
        $i->banner=$fName;
        $i->save();

        return back()->with('success', 'Banner has been uploaded successfully');
    }
}
