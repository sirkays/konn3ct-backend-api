<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Jobs\EmailInviteJob;
use App\Jobs\WhatsappInviteJob;
use App\Models\InvitesModel;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InviteController extends Controller
{
    public function invite(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'room_id' => 'required|max:255',
            'hostname' => 'required|max:255',
            'date' => 'required',
            'time' => 'required',
            'timezone' => 'required',
            'title' => 'required',
            'additional' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all(),'hint'=>'Valid types are Youtube,Facebook']);
        }

        if ($input['guest'] == "") {
            return response()->json(['success' => false, 'message' => 'Guest emails can not be empty']);
        }

        #TODO: save all emails sent to one table for campaigns later
        $input['guest'] .= "," . Auth::user()->email;

        $room=RoomModel::where([["id",$input['room_id']], ["user_id",Auth::id()]])->first();
        if(!$room){
            return response()->json(['success' => false, 'message' => 'Invalid room id']);
        }

        if($room->password_attendee!="attendee") {
            $accesscode = $room->password_attendee;
        }else {
            $accesscode = "No Access Code";
        }

        $roomlink=url('/join/')."/".$room->url;

        InvitesModel::create([
            "user_id" => Auth::id(),
            "type" => "email",
            "hostname" => $input['hostname'],
            "roomlink" => $roomlink,
            "accesscode" => $accesscode,
            "date" => $input['date'],
            "time" => $input['time'],
            "timezone" => $input['timezone'],
            "title" => $input['title'],
            "roomname" => $room->name,
            "additional" => $input['additional'],
            "guest" => $input['guest']
        ]);

        EmailInviteJob::dispatch($input)->delay(now()->addMinutes(1));


        return response()->json(['success' => true, 'message' => 'Invite Sent Successfully!']);

    }

    public function invites()
    {
        $data = InvitesModel::where('user_id', Auth::id())->latest()->paginate(10);

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $data]);
    }

    public function resendinvite($id)
    {
        $iv = InvitesModel::find($id);

        if ($iv->user_id == NULL) {
            return response()->json(['success' => false, 'message' => 'Incorrect ID supplied']);
        }

        if ($iv->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Invalid ID supplied']);
        }


        if ($iv->type == "whatsapp") {

            $input['guest'] = $iv->guest;
            $input['text'] = $iv->additional;

            InvitesModel::create([
                "user_id" => Auth::id(),
                "type" => "whatsapp",
                "roomname" => $iv->roomname,
                "additional" => $input['text'],
                "guest" => $input['guest']
            ]);


            WhatsappInviteJob::dispatch($input)->delay(now()->addSeconds(5));

        } else {
            $input['hostname'] = $iv->hostname;
            $input['roomlink'] = $iv->roomlink;
            $input['accesscode'] = $iv->accesscode;
            $input['date'] = $iv->date;
            $input['time'] = $iv->time;
            $input['timezone'] = $iv->timezone;
            $input['title'] = $iv->title;
            $input['roomname'] = $iv->roomname;
            $input['additional'] = $iv->additional;
            $input['guest'] = $iv->guest;

            InvitesModel::create([
                "user_id" => Auth::id(),
                "type" => "email",
                "hostname" => $input['hostname'],
                "roomlink" => $input['roomlink'],
                "accesscode" => $input['accesscode'],
                "date" => $input['date'],
                "time" => $input['time'],
                "timezone" => $input['timezone'],
                "title" => $input['title'],
                "roomname" => $input['roomname'],
                "additional" => $input['additional'],
                "guest" => $input['guest']
            ]);

            EmailInviteJob::dispatch($input)->delay(now()->addMinutes(1));

        }

        return response()->json(['success' => true, 'message' => 'Invite Sent Successfully!']);
    }

}
