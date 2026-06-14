<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Jobs\EmailInviteJob;
use App\Jobs\ProcessInviteContactsJob;
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
            'room_id' => 'required|int',
            'title' => 'required',
            'description' => 'nullable|string|max:200',
            'date' => 'required|date',
            'fromtime' => 'required|date_format:H:i',
            'totime' => 'required|date_format:H:i',
            'timezone' => 'required|string',
            'recurrence' => 'required|in:once,daily,weekly,monthly',
            'guest' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all()]);
        }

        if ($input['guest'] == "") {
            return response()->json(['success' => false, 'message' => 'Emails can not be empty']);
        }

        $room=RoomModel::where([["id",$input['room_id']], ["user_id",Auth::id()]])->first();
        if(!$room){
            return response()->json(['success' => false, 'message' => 'Invalid room id']);
        }

        if($room->password_attendee!="attendee") {
            $accesscode = $room->password_attendee;
        }else {
            $accesscode = "No Access Code";
        }

        $input['roomlink']=url('/join/')."/".$room->url;
        $input['accesscode']=$accesscode;
        $input['roomname']=$room->name;

        $input['date']=explode("T",$input['date'])[0];
        $input['timezone']=explode("GMT",$input['timezone'])[0];
        $input['timezone']=explode("AST",$input['timezone'])[0];
        $input['timezone']=explode("CST",$input['timezone'])[0];
        $input['timezone']=explode("EST",$input['timezone'])[0];

        // Fix hostname to use correct User columns (firstname/lastname instead of first_name/last_name)
        $hostUser = Auth::user();

        // Check for duplicate or overlapping invites for the same room and date
        $existingInvites = InvitesModel::where([
            ['user_id', $hostUser->id],
            ['date', $input['date']],
        ])->get();

        foreach ($existingInvites as $existing) {
            $existingStart = strtotime($existing->time);
            $existingEnd = strtotime($existing->totime);
            $newStart = strtotime($input['fromtime']);
            $newEnd = strtotime($input['totime']);

            // Check for overlapping time intervals
            if (
                ($newStart >= $existingStart && $newStart < $existingEnd) ||
                ($newEnd > $existingStart && $newEnd <= $existingEnd) ||
                ($newStart <= $existingStart && $newEnd >= $existingEnd)
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a meeting scheduled for this room that overlaps with the requested time (' . 
                                date('g:i A', $existingStart) . ' - ' . date('g:i A', $existingEnd) . ').'
                ]);
            }
        }
        
        $im=InvitesModel::create([
            "user_id" => $hostUser->id,
            "type" => "email",
            "shedule_type" => "meeting",
            "hostname" => $hostUser->firstname . " " . $hostUser->lastname,
            "roomlink" => $input['roomlink'],
            "accesscode" => $input['accesscode'],
            "date" => $input['date'],
            "time" => $input['fromtime'],
            "totime" => $input['totime'],
            "timezone" => $input['timezone'],
            "title" => $input['title'],
            "roomname" => $input['roomname'],
            "additional" => $input['description'],
            "recurrence" => $input['recurrence'],
            "guest" => $input['guest']
        ]);

        // Dispatch the email job
        EmailInviteJob::dispatch($im);
        
        // Dispatch the new job to create invite entries for existing Konn3ct users
        ProcessInviteContactsJob::dispatch($im);


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
            $input['totime'] = $iv->totime;
            $input['timezone'] = $iv->timezone;
            $input['title'] = $iv->title;
            $input['roomname'] = $iv->roomname;
            $input['additional'] = $iv->additional;
            $input['guest'] = $iv->guest;
            $input['recurrence'] = $iv->recurrence;
            $input['shedule_type'] = $iv->shedule_type;

            $newInvite = InvitesModel::create([
                "user_id" => Auth::id(),
                "type" => "email",
                "hostname" => $input['hostname'],
                "roomlink" => $input['roomlink'],
                "accesscode" => $input['accesscode'],
                "date" => $input['date'],
                "time" => $input['time'],
                "totime" => $input['totime'],
                "timezone" => $input['timezone'],
                "title" => $input['title'],
                "roomname" => $input['roomname'],
                "additional" => $input['additional'],
                "guest" => $input['guest'],
                "recurrence" => $input['recurrence'],
                "shedule_type" => $input['shedule_type'],
                "room_id" => $iv->room_id
            ]);

            EmailInviteJob::dispatch($newInvite)->delay(now()->addMinutes(1));

        }

        return response()->json(['success' => true, 'message' => 'Invite Sent Successfully!']);
    }

}
