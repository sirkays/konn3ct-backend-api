<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyParticipantMeetingJob;
use App\Models\EnrolledChat;
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

        if (Auth::id() != $i->user_id) {
            return response()->json(['success' => false, 'message' => 'Invalid Room!!']);
        }

        $name = Auth::user()->firstname . " " . Auth::user()->lastname;

        $ms = \Bigbluebutton::isMeetingRunning("0$i->id");

        if ($ms == 1) {
            $url = redirect()->to(
                \Bigbluebutton::join([
                    'meetingID' => "0$i->id",
                    'userName' => $name,
                    'password' => $i->password_moderator //which user role want to join set password here
                ])
            );

            return response()->json(['success' => true, 'message' => 'Meeting is still opened.', 'url' => $url]);
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
            $mdata['name'] = $name;
            $mdata['email'] = Auth::user()->email;
            $mdata['password_attendee'] = $up;
            $mdata['status'] = "start meeting";
            $mdata['identifier'] = $i->id . rand();
            MeetingsModel::create($mdata);

            $message = 'Welcome to konn3ct!<br><br>Host: ' . Auth::user()->firstname . ' <br/> Meeting Link: <a href="' . url("/join/") . '/' . $i->url . '"> ' . url("/join/") . '/' . $i->url . '</a>  <br/>Dial-In: <span style="color: #008b8b;">%%DIALNUM%%</span> <br/>SIP: ' . env('SIP_URI') . ' <br/>PIN: %%CONFNUM%%';

            $url = \Bigbluebutton::start([
                'meetingID' => "0$i->id",
                'moderatorPW' => $i->password_moderator, //moderator password set here
                'attendeePW' => $up, //attendee password here
                'meetingName' => $i->name,
                'userName' => $name,//for join meeting
                'endCallbackUrl' => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage' => $message,
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
                'avatarUrl' => 'https://konn3ct.com/assets/images/konn3ctIcon.png',
                'customParameters' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_enable_video' => 'true',
                    'userdata-bbb_listen_only_mode' => 'false',
                    'userdata-bbb_force_listen_only' => 'false',
                    'userdata-bbb_skip_check_audio' => 'true'
                ]
            ]);

            NotifyParticipantMeetingJob::dispatch($i->id);

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
                    'userdata-bbb_skip_check_audio' => 'true'
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
                    'userdata-bbb_skip_check_audio' => 'true'
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
            'meetingID' => "0$room->id",
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

        if (isset($input['welcome_message'])) {
            if ($input['welcome_message'] == "") {
                $input['welcome_message'] = 'Welcome to Host: ' . Auth::user()->firstname . '<br>Meeting Link: <a href="' . url("/join/") . '/' . $input["url"] . '" <span style="color: #008b8b;">' . url("/join/") . '/' . $input["url"] . '</span></a><br>Dial-in: <span style="color: #008b8b;">%%DIALNUM%%</span> PIN: <span style="color: #008b8b;">%%CONFNUM%%</span>';
            }
        } else {
            $input['welcome_message'] = 'Welcome to Host: ' . Auth::user()->firstname . '<br>Meeting Link: <a href="' . url("/join/") . '/' . $input["url"] . '" <span style="color: #008b8b;">' . url("/join/") . '/' . $input["url"] . '</span></a><br>Dial-in: <span style="color: #008b8b;">%%DIALNUM%%</span> PIN: <span style="color: #008b8b;">%%CONFNUM%%</span>';
        }

        if (isset($input['logout_url'])) {
            if ($input['logout_url'] == "") {
                $input['logout_url'] = url('/leftsession');
            }
        } else {
            $input['logout_url'] = url('/leftsession');
        }

        $input['max_participants'] = $max_user;
        $input['duration'] = $duration;
        $input['url'] = preg_replace('/\s+/', '', $input['url']);

        if (isset($input['access_code'])) {
            if ($input['access_code'] == "") {
                $input['password_attendee'] = "attendee";
                $input['password_moderator'] = "moderator";
            } else {
                $input['password_attendee'] = $input['access_code'];
                $input['password_moderator'] = "moderator";
            }
        } else {
            $input['password_attendee'] = "attendee";
            $input['password_moderator'] = "moderator";
        }

        $input['user_id'] = Auth::id();

        $r = RoomModel::create($input);

        EnrolledChat::create([
            'user_id' => $input['user_id'],
            'room_id' => $r->id,
            'owner' => 1
        ]);

        return response()->json(['success' => true, 'message' => 'Room Created Successfully!', 'data' => ['name' => $input['name'], 'id' => $r->id]]);
    }

    public function startRoom(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'id' => 'required|numeric|min:1',
            'name' => 'required|string|min:3|max:30',
            'started_by' => 'required|string|min:3|max:50',
            'logout_url' => 'required|string',
            'message' => 'required|string',
            'keyword' => 'nullable|string',
            'access_code' => 'nullable|string|min:6'
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

        $ms = \Bigbluebutton::isMeetingRunning($rm_id);
//        $ms = 1;

        if ($ms == 1) {
            return response()->json(['success' => true, 'message' => 'The room is opened already. Kindly join the room', '_link' => ['resource' => '/join-room', 'method' => 'POST']]);
        }

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

        if (isset($input['access_code'])) {
            $i->password_attendee = $input['access_code'];
            $i->save();
        } else {
            $i->password_attendee = "password";
            $i->save();
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
        $mdata['keyword'] = $input['keyword'] ?? '';

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
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|email|min:3',
            'role' => 'nullable|string|min:3',
            'access_code' => 'nullable|string|min:6',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['success' => false, 'message' => 'Error in your request', 'errors' => $validator->errors()]);
        }

        $roles = ['moderator', 'viewer'];
        $role = 'viewer';

        $id = $input['id'];
        $name = $input['name'];
        $email = $input['email'];

        if (isset($input['role'])) {
            if (!in_array($input['role'], $roles)) {
                return response()->json(['success' => false, 'message' => 'Role does not exist', '_available' => $roles]);
            }
            $role = $input['role'];
        }

        $room = RoomModel::where([['id', $id], ["user_id", Auth::id()]])->first();
        $i = $room;

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        $rm_id = "0$i->id";

        $ms = \Bigbluebutton::isMeetingRunning($rm_id);
//        $ms = 1;

        if ($ms != 1) {
            return response()->json(['success' => false, 'message' => 'Rooms not started. Kindly start and try again', '_link' => ['resource' => '/start-room', 'method' => 'POST']]);
        }

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
        }

        if ($i->password_attendee != "password") {
            if (isset($input['access_code'])) {
                if ($input['access_code'] == $i->password_attendee) {
                    if ($role == $roles[0]) {
                        $password = $i->password_moderator;
                    } else {
                        $password = $i->password_attendee;
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Incorrect access code supplied']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Access code is required']);
            }
        } else {
            if ($role == $roles[0]) {
                $password = $i->password_moderator;
            } else {
                $password = $i->password_attendee;
            }
        }

        $fm = MeetingsModel::where('meeting_id', '=', $i->id)->orderBy('id', 'desc')->first();

        $mdata['identifier'] = $fm->identifier;
        $mdata['status'] = "attempt_to_join";
        $mdata['meeting_id'] = $i->id;
        $mdata['name'] = $name;
        $mdata['email'] = $email;
        $mdata['password_attendee'] = $password;
        MeetingsModel::create($mdata);


        try {
            $url = \Bigbluebutton::join([
                'meetingID' => $rm_id,
                'userName' => $name,
                'userId' => $email,
                'password' => $password, //which user role want to join set password here
                'avatarUrl' => $dp,
                'customParameters' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_enable_video' => 'true',
                    'userdata-bbb_listen_only_mode' => 'false',
                    'userdata-bbb_force_listen_only' => 'false',
                    'userdata-bbb_skip_check_audio' => 'true'
                ],
            ]);

            $murl = explode("?", $url);
            $end = encrypt($murl[1]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Maybe Room not started. Kindly start and try again', '_link' => ['resource' => '/start-room', 'method' => 'POST']]);
        }

        return response()->json(['success' => true, 'message' => 'Rooms fetched successfully', 'data' => url('/userjoin') . '/' . $end]);

    }

    public function joinAppRoom(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'id' => 'required|numeric|min:1',
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|email|min:3',
            'access_code' => 'nullable|string|min:1',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {

            return response()->json(['success' => false, 'message' => 'Error in your request', 'errors' => $validator->errors()]);
        }

        $id = $input['id'];
        $name = $input['name'];
        $email = $input['email'];

        $room = RoomModel::where('id', $id)->first();
        $i = $room;

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }


        if ($i->password_attendee != "attendee") {
            if (isset($input['access_code'])) {
                if ($input['access_code'] == $i->password_attendee) {
                    $password = $i->password_attendee;
                } else {
                    return response()->json(['success' => false, 'message' => 'Incorrect access code supplied']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Access code is required']);
            }
        } else {
            $password = $i->password_attendee;
        }

        $rm_id = "0$i->id";

        $ms = \Bigbluebutton::isMeetingRunning($rm_id);

        if ($ms != 1) {
            return response()->json(['success' => false, 'message' => 'Rooms not started. Kindly start and try again', '_link' => ['resource' => '/start-room', 'method' => 'POST']]);
        }

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
        }

        $fm = MeetingsModel::where('meeting_id', '=', $i->id)->orderBy('id', 'desc')->first();

        $mdata['identifier'] = $fm->identifier;
        $mdata['status'] = "attempt_to_join_app";
        $mdata['meeting_id'] = $i->id;
        $mdata['name'] = $name;
        $mdata['email'] = $email;
        $mdata['password_attendee'] = $password;
        MeetingsModel::create($mdata);

        $url = \Bigbluebutton::join([
            'meetingID' => $rm_id,
            'userName' => $name,
            'userId' => $email,
            'password' => $password, //which user role want to join set password here
            'avatarUrl' => $dp,
            'customParameters' => [
                'userdata-bbb_auto_join_audio' => 'true',
                'userdata-bbb_enable_video' => 'true',
                'userdata-bbb_listen_only_mode' => 'false',
                'userdata-bbb_force_listen_only' => 'false',
                'userdata-bbb_skip_check_audio' => 'true'
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Room joined successfully', 'data' => $url]);

    }

    public function roomInfo($id)
    {
        $room = RoomModel::where([['id', $id], ["user_id", Auth::id()]])->first();

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        $rm_id = "0$room->id";


//        $ms = \Bigbluebutton::isMeetingRunning($rm_id);
//
//        if ($ms != 1) {
//            return response()->json(['success' => false, 'message' => 'Rooms not started. Kindly start and try again', '_link' => ['resource' => '/start-room', 'method' => 'POST']]);
//        }

        try {

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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Rooms not started. Kindly start and try again', '_link' => ['resource' => '/start-room', 'method' => 'POST']]);
        }

        return response()->json(['success' => true, 'message' => 'Rooms details', 'data' => $datas]);
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

    public function meetingInfo(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Name not found in your request', 'error' => $validator->errors()]);
        }

        $name = $input['name'];

        $room = RoomModel::where([['name', $name], ['user_id', Auth::id()]])->orwhere([['user_id', Auth::id()], ["url", $name]])->first();

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Rooms does not exist']);
        }

        return response()->json(['success' => true, 'message' => 'Meeting validated successfully', 'data' => $room]);
    }

    public function meetingHistory()
    {
        $meeting = MeetingsModel::where('email', Auth::user()->email)->latest()->limit(10)->with('room')->get();


        return response()->json(['success' => true, 'message' => 'Meeting history fetched successfully', 'data' => $meeting]);
    }

}
