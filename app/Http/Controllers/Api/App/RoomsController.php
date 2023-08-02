<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Jobs\WhatsappAppInviteAllJob;
use App\Models\MeetingsModel;
use App\Models\RoomModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{
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

        $rm_id = "$i->id";

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
