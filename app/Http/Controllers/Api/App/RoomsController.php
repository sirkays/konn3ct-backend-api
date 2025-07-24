<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Jobs\KCEnrollOwnerJob;
use App\Jobs\WhatsappAppInviteAllJob;
use App\Models\EnrolledChat;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Services\MeetingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{

    public function create(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'url' => 'nullable|unique:room',
            'dial_number' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $plan = PlanModel::where("id", Auth::user()->plan)->first();
        $r = $plan->rooms + Auth::user()->room_bundles;
        $duration = $plan->duration;
        $max_user=$plan->participant;

        $rc=RoomModel::where("user_id",Auth::id())->count();

        if($rc >= $r){
            return response()->json(['success' => false, 'message' => 'Maximum room(s) exceeded for your current plan!']);
        }

        if ($input['url']==""){
            $num=trim(date('siyh'));
            $shuffled = str_shuffle(substr(Auth::user()->firstname, 0, 2) . substr(str_shuffle($num), 0, 4));
            $sfinal = substr($shuffled, 0, 6);

            if (Auth::user()->lastname == "") {
                $input['url'] = trim(substr(Auth::user()->firstname, 0, 3) . $sfinal);
            } else {
                $input['url'] = trim(substr(Auth::user()->lastname, 0, 3) . $sfinal);
            }
        }

        $input['url'] = str_replace(' ', '', $input['url']);

        $input['welcome_message'] = "";
        $input['logout_url'] = url('/leftsession');
        $input['max_participants'] = $max_user;
        $input['duration'] = $duration;
        $input['url'] = preg_replace('/\s+/', '', $input['url']);

        if (!isset($input['access_code'])) {
            $input['password_attendee'] = "attendee";
            $input['password_moderator'] = "moderator";
        } else {
            $input['password_attendee'] = $input['access_code'];
            $input['password_moderator'] = "moderator";
        }

        $input['user_id'] = Auth::id();

        $r = RoomModel::create($input);

        KCEnrollOwnerJob::dispatch($r->id, Auth::id())->delay(now()->addSeconds(1));

        return response()->json(['success' => false, 'message' => 'Room Created Successfully!']);
    }

    public function show(){
        $plan = PlanModel::where("id", Auth::user()->plan)->first();
        $r = $plan->rooms + Auth::user()->room_bundles;

        $datas['max_rooms'] = $r;

        $datas['rooms'] = RoomModel::where("user_id", Auth::id())->orderBy('id', 'asc')->with('prereg_model')->limit($r)->get();
        $datas['total_rooms'] = RoomModel::where("user_id", Auth::id())->count();
        if ($datas['total_rooms'] > $r) {
            $datas['total_rooms'] = $r;
        }
        $datas['plan'] = PlanModel::where("id", Auth::user()->plan)->first();

        $datas['active_rooms'] = 0;
        $datas['private_rooms'] = 1;

        $fpr=RoomModel::where([["user_id", Auth::id()], ['default_room', 'yes']])->orderBy('id', 'asc')->first();

        if(!$fpr){
            $datas['personal_room'] = count($datas['rooms']) > 0 ? $datas['rooms'][0] : null;
        }else{
            $datas['personal_room'] = $fpr;
        }

        $datas['room_link'] = env('MJOIN_INTERFACE')."/join/replaceRoomURL";
        $datas['video_link'] = 'https://www.youtube.com/watch?v=xj-0hQJvmPo';

        if (!App::environment(['local', 'staging'])) {
            foreach ($datas['rooms'] as $i) {
                $ms = \Bigbluebutton::isMeetingRunning("0$i->id");
                if ($ms) {
                    $datas['active']++;
                }
            }
        }

        if($datas['active_rooms']>$r){
            $datas['active_rooms']=$r;
        }

        return response()->json(['success' => true, 'message' => 'Room fetched successfully', 'data' => $datas]);
    }

    public function mStartRoom($id){

        $i = RoomModel::find($id);

        if (!$i) {
            return response()->json(['success' => false, 'message' => 'Invalid Room!']);
        }

        $ms = MeetingService::meetingStatus($i);

        if (!$ms) {
            MeetingService::startMeeting(Auth::user(),$i);
        }

        $url=MeetingService::joinMeeting($i,Auth::user()->email,Auth::user()->lastname . " " . Auth::user()->firstname,$i->password_moderator);

        if(!$url){
            return response()->json(['success' => false, 'message' => 'Unable to join']);
        }

        return response()->json(['success' => true, 'message' => 'Meeting started and joined successfully.', 'url' => $url, 'sessionToken'=> explode("=",$url)[1]]);
    }

    public function delete(Request $request, $id){

        $i = RoomModel::find($id);

        if (!$i) {
            return response()->json(['success' => false, 'message' => 'Invalid RoomID!']);
        }

        if ($i->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Invalid Room!']);
        }

        $i->delete();

        EnrolledChat::where("room_id", $i->id)->update(['status' => '0']);

        return response()->json(['success' => false, 'message' => 'Room Deleted Successfully!']);
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
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
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

        $ms = MeetingService::meetingStatus($i);

        if (!$ms) {
            return response()->json(['success' => false, 'message' => 'Rooms not started. Kindly start and try again', '_link' => ['resource' => '/start-room', 'method' => 'POST']]);
        }

        $url=MeetingService::joinMeeting($i,$email,$name,$password);

        if(!$url){
            return response()->json(['success' => false, 'message' => 'Unable to join']);
        }

        return response()->json(['success' => true, 'message' => 'Room joined successfully', 'data' => $url, 'sessionToken'=> explode("=",$url)[1]]);
    }

    public function joinRoomkv4(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'room' => 'required|string|min:3',
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|email|min:3',
            'access_code' => 'nullable|string',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $roles = ['moderator', 'viewer'];
        $role = 'viewer';

        $room = $input['room'];
        $name = $input['name'];
        $email = $input['email'];

        $i = RoomModel::where('url', $room)->orWhere('name', $room)->first();

        if (!$i) {
            return response()->json(['success' => false, 'message' => 'Room url or name does not exist, kindly check your input and try again!']);
        }

        $ms = MeetingService::meetingStatus($i);

        if (!$ms) {
            return response()->json(['success' => false, 'message' => 'Room not started. Kindly try again later']);
        }

        if ($i->password_attendee != "attendee") {
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

        $url=MeetingService::joinMeeting($i,$email,$name,$password);

        if(!$url){
            return response()->json(['success' => false, 'message' => 'Unable to join']);
        }

        $wait=str_contains($url,'guestWait');

        return response()->json(['success' => true, 'message' => 'Room joined successfully', 'data' =>explode("=",$url)[1], 'wait'=>$wait]);
    }

    public function inviteAll(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'phones' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all()]);
        }

        $name = Auth::user()->lastname . " " . Auth::user()->firstname;

        Log::info("App Invite initiated by $name");
        Log::info("Phones: " . $input['phones']);

        WhatsappAppInviteAllJob::dispatch($input['phones'], $name);

        return response()->json(['success' => true, 'message' => 'Invite has started sending in background']);
    }
}
