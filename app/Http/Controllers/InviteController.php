<?php

namespace App\Http\Controllers;

use App\Jobs\EmailInviteJob;
use App\Jobs\WhatsappInviteJob;
use App\Models\InvitesModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function invite(Request $request)
    {
        $input = $request->all();

        if ($input['guest'] == "") {
            return back()->with('error', 'Guest emails can not be empty');
        }

        #TODO: save all emails sent to one table for campaigns later
        $input['guest'] .= "," . Auth::user()->email;

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

        return redirect('room')->with('success', 'Invite Sent Successfully!');
    }

    public function invite_whatsapp(Request $request)
    {
        $input = $request->all();

        if ($input['guest'] == "") {
            return back()->with('error', 'Guest numbers can not be empty');
        }

        InvitesModel::create([
            "user_id" => Auth::id(),
            "type" => "whatsapp",
            "hostname" => $input['hostname'],
            "roomlink" => $input['roomlink'],
            "accesscode" => $input['accesscode'],
            "date" => $input['date'],
            "time" => $input['time'],
            "timezone" => $input['timezone'],
            "title" => $input['title'],
            "roomname" => $input['roomname'],
            "guest" => $input['guest']
        ]);


        WhatsappInviteJob::dispatch($input)->delay(now()->addSeconds(5));

        $user = User::find(Auth::id());
        if ($user->whatsapp_invite == "0") {
            $user->whatsapp_invite = Carbon::now()->addDays(8);
            $user->save();
        }

        return redirect('room')->with('success', 'Invite Sent Successfully!');
    }

    public function invites()
    {
        $data['invites'] = InvitesModel::where('user_id', Auth::id())->latest()->get();
        $data['i'] = 1;

        return view('user.invites', $data);
    }

    public function resendinvite($id)
    {
        $iv = InvitesModel::find($id);

        if ($iv->user_id == NULL) {
            return redirect()->to("invites")
                ->with('error', 'Invalid ID supplied');
        }

        if ($iv->user_id != Auth::id()) {
            return redirect()->to("invites")
                ->with('error', 'Invalid ID supplied');
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

        return redirect('invites')->with('success', 'Invite Sent Successfully!');
    }

}
