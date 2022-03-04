<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeetingsModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function startaRoom($id)
    {

        $i = RoomModel::find($id);

        if (!$i) {
            return response()->json(['success' => false, 'message' => 'Invalid Room!']);
        }

        $ms = \Bigbluebutton::isMeetingRunning($i->id);

        if ($ms == 1) {
            return redirect()->to(
                \Bigbluebutton::join([
                    'meetingID' => $i->id,
                    'userName' => "Samji test",
                    'password' => $i->password_moderator //which user role want to join set password here
                ])
            );
        } else {
            $plan = PlanModel::where("id", 2)->first();
            if ($plan->recording) {
                $record = true; //overwrite default configuration
            } else {
                $record = false; //overwrite default configuration
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

            $mdata['meeting_id'] = $i->id;
            $mdata['name'] = "samji via api";
            $mdata['email'] = "samjiviaapi@gmail.com";
            $mdata['password_attendee'] = $up;
            $mdata['status'] = "start meeting";
            $mdata['identifier'] = $i->id . rand();
            MeetingsModel::create($mdata);

            $url = \Bigbluebutton::start([
                'meetingID' => "0$i->id",
                'moderatorPW' => $i->password_moderator, //moderator password set here
                'attendeePW' => $up, //attendee password here
                'meetingName' => $i->name,
                'userName' => "samji api",//for join meeting
                'endCallbackUrl' => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage' => 'Welcome to <span style="color: #008b8b;"> konn3ct!</span><br><br> API Test',
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
                'avatarUrl' => 'https://dev.konn3ct.net/assets/images/konn3ctIcon.png',
                'customParameters' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_enable_video' => 'true',
                    'userdata-bbb_listen_only_mode' => 'false',
                    'userdata-bbb_force_listen_only' => 'false',
                    'userdata-bbb_skip_check_audio' => 'true'
                ]
            ]);

            return response()->json(['success' => true, 'message' => 'Meeting started successfully.', 'url' => $url]);
        }

    }

    public function startRoomO(Request $request)
    {
        $input = $request->all();

        $roomid = $input['room'];
        $room_name = $input['room_name'];
        $email = $input['user_email'];
        $name = $input['user_name'];

        $u = User::where("email", $email)->first();
        if (!$u) {
            return response()->json(['success' => false, 'message' => 'User does not exist']);
        }

        $room_name = str_replace("-", " ", $room_name);

        $rm = RoomModel::where('name', $room_name)->first();

        if ($rm) {
            $roomid = "0$rm->id";
            $room_name = $rm->name;
        }

        $ms = \Bigbluebutton::isMeetingRunning($roomid);
        $dp = 'https://konn3ct.com/assets/images/konn3ctIcon.png';

        if ($ms != 1) {
            $plan = PlanModel::where("id", $u->plan)->first();
            if ($plan->recording) {
                $record = true; //overwrite default configuration
            } else {
                $record = false; //overwrite default configuration
            }

            $duration = $plan->duration;
            $max_user = $plan->participant;


            $banner = "https://konn3ct.com/assets/images/konn3ct_logo.png";
            $up = "attendee";
            $dsn = false;
            $dum = false;
            $muj = false;
            $dpuc = false;
            $dprc = false;
            $ewma = false;
            $link = url("/join/") . '/' . $rm->url;
            $welcomeMSG = 'Welcome to  konn3ct!<br><br>Host: ' . $host_name . '<br>Meeting Link: <a href="' . $link . '" <span style="color: #008b8b;">' . $link . '</span></a><br/>Dial-In: %%DIALNUM%% PIN: %%CONFNUM%%';


            \Bigbluebutton::create([
                'meetingID' => $roomid,
                'moderatorPW' => $rm->password_moderator, //moderator password set here
                'attendeePW' => $rm->password_attendee, //attendee password here
                'meetingName' => $rm->name,
                'endCallbackUrl' => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage' => $welcomeMSG,
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
        } else {
            $url = \Bigbluebutton::join([
                'meetingID' => $roomid,
                'userName' => $name,
                'password' => $rm->password_moderator, //which user role want to join set password here
                'avatarUrl' => $dp,
                'customParameters' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_enable_video' => 'true',
                    'userdata-bbb_listen_only_mode' => 'false',
                    'userdata-bbb_force_listen_only' => 'false',
                    'userdata-bbb_skip_check_audio' => 'true',
                    'userdata-bbb_user_email' => $email
                ],
            ]);
        }

        if ($ms == 1) {
            $url = \Bigbluebutton::join([
                'meetingID' => $roomid,
                'userName' => $name,
                'password' => "attendee" //which user role want to join set password here
            ]);
        } else {
            $plan = PlanModel::where("id", $u->plan)->first();
            if ($plan->recording) {
                $record = true; //overwrite default configuration
            } else {
                $record = false; //overwrite default configuration
            }

            $duration = $plan->duration;
            $max_user = $plan->participant;


            $banner = "https://konn3ct.com/assets/images/konn3ct_logo.png";
            $up = "attendee";
            $dsn = false;
            $dum = false;
            $muj = false;
            $dpuc = false;
            $dprc = false;
            $ewma = false;

//            $mdata['meeting_id'] = $roomid;
//            $mdata['name'] = "samji via api";
//            $mdata['email'] = "samjiviaapi@gmail.com";
//            $mdata['password_attendee'] = $up;
//            $mdata['status'] = "start meeting";
//            $mdata['identifier'] = $roomid . rand();
//            MeetingsModel::create($mdata);

            $url = \Bigbluebutton::start([
                'meetingID' => $roomid,
                'moderatorPW' => "moderator", //moderator password set here
                'attendeePW' => $up, //attendee password here
                'meetingName' => $room_name,
                'userName' => $name,//for join meeting
                'endCallbackUrl' => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage' => 'Welcome to <span style="color: #008b8b;"> konn3ct!</span><br><br>',
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
                'avatarUrl' => 'https://dev.konn3ct.net/assets/images/konn3ctIcon.png',
                'customParameters' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_enable_video' => 'true',
                    'userdata-bbb_listen_only_mode' => 'false',
                    'userdata-bbb_force_listen_only' => 'false',
                    'userdata-bbb_skip_check_audio' => 'true',
                    'userdata-bbb_user_email' => Auth::user()->email
                ]
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Meeting started successfully.', 'url' => $url]);

    }

    public function checkRoom(Request $request)
    {
        $input = $request->all();

        $roomid = $input['room'];
        $room_name = $input['room_name'];
        $email = $input['email'];

        $room_name = str_replace("-", " ", $room_name);

        $rm = RoomModel::where('name', $room_name)->first();

        $u = User::where('email', $email)->first();
        $owner = false;

        if (!$u) {
            return response()->json(['success' => false, 'message' => 'User does not exist', 'owner' => $owner]);
        }

        if (!$rm) {
            return response()->json(['success' => false, 'message' => 'Room does not exist', 'owner' => $owner]);
        }

        if ($rm) {
            $roomid = "0$rm->id";
        }

        $ms = \Bigbluebutton::isMeetingRunning($roomid);

        if ($u->id == $rm->user_id) {
            $owner = true;
        }

        if ($ms == 1) {
            return response()->json(['success' => true, 'message' => 'Meeting is active', 'owner' => $owner]);
        }
        return response()->json(['success' => false, 'message' => 'Meeting is inactive', 'owner' => $owner]);
    }

    public function fetchRooms($email)
    {
        $u = User::where("email", $email)->first();

        if ($u == null) {
            return response()->json(['success' => false, 'message' => 'User does not exist']);
        }
        $rooms = RoomModel::where("user_id", $u->id)->get();

        return response()->json(['success' => true, 'message' => 'Rooms fetched successfully', 'data' => $rooms]);
    }

    /**
     * @param $timestamp
     * @return array
     */
    function generate_sec_key($timestamp = null): array
    {
        $timestamp = Carbon::now();
        $plaintext = intval(env("SMILE_PARTNER_ID")) . ":" . $timestamp;
        $hash_signature = hash('sha256', $plaintext);
        $sec_key = '';
        openssl_public_encrypt($hash_signature, $sec_key, base64_decode(env("SMILE_API_KEY")), OPENSSL_PKCS1_PADDING);
        $sec_key = base64_encode($sec_key);
        $sec_key = $sec_key . "|" . $hash_signature;
        return array("sec_key" => $sec_key, "timestamp" => $timestamp);
    }

    function confirm_sec_key($sec_key): bool
    {
        $sec_key_exploded = explode("|", $sec_key);
        $encrypted = base64_decode($sec_key_exploded[0]);
        $hash_signature = $sec_key_exploded[1];
        $decrypted = '';
        openssl_public_decrypt($encrypted, $decrypted, base64_decode($this->api_key), OPENSSL_PKCS1_PADDING);
        return $hash_signature == $decrypted;
    }

    /**
     * @param $timestamp
     * @return array
     */
    function generate_signature($timestamp = null): array
    {
        $timestamp = $timestamp != null ? $timestamp : Clock::now()->format(DateTimeInterface::ISO8601);
        $message = $timestamp . $this->partner_id . "sid_request";
        $sec_key = base64_encode(hash_hmac('sha256', $message, $this->api_key, true));
        return array("signature" => $sec_key, "timestamp" => $timestamp);
    }

    /**
     * @param $timestamp
     * @param string $signature
     * @return bool
     */
    function confirm_signature($timestamp, string $signature): bool
    {
        return $signature === $this->generate_signature($timestamp)["signature"];
    }

    /**
     * @param $timestamp
     * @return bool
     */
    private function isTimestamp($timestamp): bool
    {
        if (ctype_digit($timestamp) && strtotime(date('Y-m-d H:i:s', $timestamp)) === (int)$timestamp) {
            return true;
        } else {
            return false;
        }
    }


    public function listRooms()
    {
        $rooms = RoomModel::where("user_id", Auth::id())->latest()->select('id', 'name', 'url', 'logout_url', 'welcome_message', 'max_participants', 'duration', 'banner', 'created_at')->get();

        return response()->json(['success' => true, 'message' => 'Rooms fetched successfully', 'data' => $rooms]);
    }

    public function roomRecordings($id)
    {
        $room = RoomModel::where([['id', $id], ["user_id", Auth::id()]])->first();

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        $recordings = \Bigbluebutton::getRecordings([
            'meetingID' => $room->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Rooms recording fetched successfully', 'data' => $recordings]);
    }

    public function allRecordings()
    {
        $rooms = RoomModel::where("user_id", Auth::id())->get();

        $fer = [];

        foreach ($rooms as $r) {
            array_push($fer, $r->id);
            array_push($fer, "0$r->id");
        }

        $recordings = \Bigbluebutton::getRecordings([
            'meetingID' => $fer,
        ]);

        return response()->json(['success' => true, 'message' => 'All Rooms recording fetched successfully', 'data' => $recordings]);
    }

    public function createRoom(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'name' => 'required|string|min:3|max:50',
            'logout_url' => 'nullable|url',
            'access_code' => 'nullable|string',
            'welcome_message' => 'nullable|string'
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['success' => false, 'message' => 'Error in your request', 'errors' => $validator->errors()]);
        }


        $plan = PlanModel::where("id", Auth::user()->plan)->first();
        $r = $plan->rooms + Auth::user()->room_bundles;
        $duration = $plan->duration;
        $max_user = $plan->participant;

        $rc = RoomModel::where("user_id", Auth::id())->count();

        if ($rc >= $r) {
            return response()->json(['success' => false, 'message' => 'Maximum room(s) exceeded for your current plan!']);
        }

        $num = trim(date('siyh'));
        $shuffled = str_shuffle(substr(Auth::user()->firstname, 0, 2) . substr(str_shuffle($num), 0, 4));
        $sfinal = substr($shuffled, 0, 6);

        if (Auth::user()->lastname == "") {
            $input['url'] = trim(substr(Auth::user()->firstname, 0, 3) . $sfinal);
        } else {
            $input['url'] = trim(substr(Auth::user()->lastname, 0, 3) . $sfinal);
        }

        if ($input['welcome_message'] == "") {
            $input['welcome_message'] = 'Welcome to Host: ' . Auth::user()->firstname . '<br>Meeting Link: <a href="' . url("/join/") . '/' . $input["url"] . '" <span style="color: #008b8b;">' . url("/join/") . '/' . $input["url"] . '</span></a><br>Dial-in: <span style="color: #008b8b;">%%DIALNUM%%</span> PIN: <span style="color: #008b8b;">%%CONFNUM%%</span>';
        }

        if ($input['logout_url'] == "") {
            $input['logout_url'] = url('/leftsession');
        }

        $input['max_participants'] = $max_user;
        $input['duration'] = $duration;
        $input['url'] = preg_replace('/\s+/', '', $input['url']);

        if ($input['access_code'] == "") {
            $input['password_attendee'] = "attendee";
            $input['password_moderator'] = "moderator";
        } else {
            $input['password_attendee'] = $input['access_code'];
            $input['password_moderator'] = "moderator";
        }

        $r = RoomModel::create($input);

        $createMeeting = \Bigbluebutton::initCreateMeeting([
            'meetingID' => "0$r->id",
            'meetingName' => $input['name'],
            'attendeePW' => $input['password_attendee'],
            'moderatorPW' => $input['password_moderator'],
            'endCallbackUrl' => url('/leftsession'),
            'logoutUrl' => $input['logout_url'],
        ]);

        $createMeeting->setDuration($duration); //overwrite default configuration
        if ($plan->dialin) {
            $createMeeting->setDialNumber("111222"); //overwrite default configuration
        }
        if ($plan->recording) {
            $createMeeting->setRecord(true); //overwrite default configuration
            $createMeeting->setAllowStartStopRecording(true); //overwrite default configuration
        } else {
            $createMeeting->setRecord(false); //overwrite default configuration
            $createMeeting->setAllowStartStopRecording(false); //overwrite default configuration
        }
        $createMeeting->setMaxParticipants($max_user); //overwrite default configuration
        $createMeeting->setWelcomeMessage($input['welcome_message']); //overwrite default configuration

        if (isset($input['muj'])) {
            $createMeeting->setMuteOnStart(true); //overwrite default configuration
        }

        if (isset($input['dpuc'])) {
            $createMeeting->setLockSettingsDisablePublicChat(true); //overwrite default configuration
        }

        if (isset($input['dprc'])) {
            $createMeeting->setLockSettingsDisablePrivateChat(true); //overwrite default configuration
        }

        if (isset($input['ewma'])) {
            $createMeeting->setLockSettingsDisableCam(true); //overwrite default configuration
        }

        if (isset($input['dum'])) {
            $createMeeting->setLockSettingsDisableMic(true); //overwrite default configuration
        }

        if (isset($input['dsn'])) {
            $createMeeting->setLockSettingsDisableNote(true); //overwrite default configuration
        }

        $bbb = \Bigbluebutton::create($createMeeting);
//       $bbb='{"returncode":"SUCCESS","internalMeetingID":"b1d5781111d84f7b3fe45a0852e59758cd7a87e5-1602475017235","parentMeetingID":"bbb-none","createTime":"1602475017235","voiceBridge":"09857","dialNumber":"613-555-1234","createDate":"Mon Oct 12 03:56:57 UTC 2020","hasUserJoined":"false","duration":"100","hasBeenForciblyEnded":"false","messageKey":[],"message":[]}';

        $bba = json_decode($bbb, true);
        $rm = RoomModel::find($r->id);

        if ($bba["returncode"] == "SUCCESS") {
            $rm->user_id = Auth::id();
            $rm->bbb_returncode = $bba["returncode"];
            $rm->internalMeetingID = $bba["internalMeetingID"];
            $rm->parentMeetingID = $bba["parentMeetingID"];
            $rm->voiceBridge = $bba["voiceBridge"];
            $rm->createDate = $bba["createDate"];
            $rm->createTime = $bba["createTime"];
            $rm->save();

            return response()->json(['success' => true, 'message' => 'Room Created Successfully!', 'data' => ['name' => $input['name'], 'id' => $r->id]]);

        } else {
            $rm->delete();
            return response()->json(['success' => false, 'message' => 'Server Error while creating Meeting!']);
        }
    }

    public function startRoom(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'id' => 'required|numeric|min:1',
            'name' => 'required|string|min:3|max:30',
            'started_by' => 'required|string|min:3|max:50',
            'logout_url' => 'required|string',
            'message' => 'required|string'
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {

            return response()->json(['success' => false, 'message' => 'Error in your request', 'errors' => $validator->errors()]);
        }

        $id = $input['id'];
        $name = $input['name'];
        $logouturl = $input['logout_url'];
        $message = $input['message'];

        $room = RoomModel::where([['id', $id], ["user_id", Auth::id()]])->first();
        $i = $room;

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        $rm_id = "0$i->id";

//        $ms = \Bigbluebutton::isMeetingRunning($rm_id);
//
//        if ($ms != 1){
//            return response()->json(['success' => true, 'message' => 'The room is open. Kindly join the room', '_link' => ['resource' => '/join-room', 'method' => 'POST']]);
//        }

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
        $mdata['name'] = $input['started_by'];
        $mdata['email'] = Auth::user()->email;
        $mdata['password_attendee'] = $up;
        $mdata['status'] = "start meeting";
        $mdata['identifier'] = $i->id . rand();
        MeetingsModel::create($mdata);

        \Bigbluebutton::create([
            'meetingID' => $rm_id,
            'moderatorPW' => $i->password_moderator, //moderator password set here
            'attendeePW' => $up, //attendee password here
            'meetingName' => $name,
            'endCallbackUrl' => url('/leftsession'),
            'logoutUrl' => $logouturl,
            'welcomeMessage' => $message,
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

        return response()->json(['success' => true, 'message' => 'Rooms started successfully. You can now join the room', '_link' => ['resource' => '/join-room', 'method' => 'POST']]);

    }

    public function joinRoom(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'id' => 'required|numeric|min:1',
            'name' => 'required|string|min:3|max:20',
            'email' => 'required|email|min:3',
//            'role' => 'required|string|min:3',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {

            return response()->json(['success' => false, 'message' => 'Error in your request', 'errors' => $validator->errors()]);
        }

        $id = $input['id'];
        $name = $input['name'];
        $email = $input['email'];

        $room = RoomModel::where([['id', $id], ["user_id", Auth::id()]])->first();
        $i = $room;

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        $rm_id = "0$i->id";

//        $ms = \Bigbluebutton::isMeetingRunning($rm_id);
//
//        if ($ms != 1) {
//            return response()->json(['success' => false, 'message' => 'Rooms not started. Kindly start and try again', '_link' => ['resource' => '/start-room', 'method' => 'POST']]);
//        }

        $u = User::where('email', $email)->first();
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

        $fm = MeetingsModel::where('meeting_id', '=', $i->id)->orderBy('id', 'desc')->first();

        $mdata['identifier'] = $fm->identifier;
        $mdata['status'] = "attempt_to_join";
        $mdata['meeting_id'] = $i->id;
        $mdata['name'] = $name;
        $mdata['email'] = $email;
        $mdata['password_attendee'] = $i->password_attendee;
        MeetingsModel::create($mdata);

        $url = \Bigbluebutton::join([
            'meetingID' => $rm_id,
            'userName' => $name,
            'userId' => $email,
            'password' => $i->password_moderator, //which user role want to join set password here
            'avatarUrl' => $dp,
            'customParameters' => [
                'userdata-bbb_auto_join_audio' => 'true',
                'userdata-bbb_enable_video' => 'true',
                'userdata-bbb_listen_only_mode' => 'false',
                'userdata-bbb_force_listen_only' => 'false',
                'userdata-bbb_skip_check_audio' => 'true',
                'userdata-bbb_user_email' => $email
            ],
        ]);

        $murl = explode("?", $url);

        $end = encrypt($murl[1]);

        return response()->json(['success' => true, 'message' => 'Rooms fetched successfully', 'data' => url('/userjoin') . '/' . $end]);

    }

    public function roomInfo($id)
    {
        $room = RoomModel::where([['id', $id], ["user_id", Auth::id()]])->first();

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        $rm_id = "0$room->id";

        $details = \Bigbluebutton::getMeetingInfo([
            'meetingID' => $rm_id,
            'moderatorPW' => $room->password_moderator
        ]);

        $datas['meetingName'] = $details['meetingName'];
        $datas['startTime'] = $details['createDate'];
        $datas['opened'] = $details['running'];
        $datas['duration'] = $details['duration'];
        $datas['hasParticipantJoined'] = $details['hasUserJoined'];
        $datas['recordingEnabled'] = $details['recording'];
        $datas['recordingEnabled'] = $details['recording'];
        $datas['participants'] = $details['participantCount'];
        $datas['participantsHasVideoOn'] = $details['videoCount'];
        $datas['admins'] = $details['moderatorCount'];
        $datas['participantLists'] = $details['moderatorCount'];

        return response()->json(['success' => true, 'message' => 'Rooms details', 'datal' => $datas, 'data' => $details]);
    }

    public function listAttendance($id)
    {
        $room = RoomModel::where([['id', $id], ["user_id", Auth::id()]])->first();

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        $attendance = MeetingsModel::orderBy('id', 'desc')
            ->where('status', '=', 'start meeting')
            ->where('meeting_id', '=', $id)
            ->get();

        return response()->json(['success' => true, 'message' => 'Room attendance fetched successfully', 'data' => $attendance->makeHidden(['password_attendee', 'updated_at'])]);
    }

    public function attendanceDetails($id, $identifier)
    {
        $attendance = MeetingsModel::orderBy('id', 'desc')
            ->where('identifier', '=', $identifier)
            ->where('meeting_id', '=', $id)
            ->get();

        return response()->json(['success' => true, 'message' => 'Attendance fetched successfully', 'data' => $attendance->makeHidden(['password_attendee', 'updated_at'])]);
    }

}
