<?php

namespace App\Http\Controllers;

use App\Mail\PreregParticipantMail;
use App\Models\Faq;
use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use App\Models\RoomModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PreregistrationController extends Controller
{
    public function prereg(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'hostname' => 'required',
            'date' => 'required',
            'time' => 'required',
            'timezone' => 'required',
            'about' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $reglink = Str::random(20);

        PreRegModel::create([
            "user_id" => Auth::id(),
            "room_id" => $input['id'],
            "reference" => $reglink,
            "title" => $input['title'],
            "host_name" => $input['hostname'],
            "date" => $input['date'],
            "time" => $input['time'],
            "timezone" => $input['timezone'],
            "about" => $input['about'],
        ]);

        $r = RoomModel::find($input['id']);
        $r->prereg = $reglink;
        $r->save();


        return back()->with('success', 'Processed successfully. Your Pre-registration link is <a href="' . url("/preregistration/") . "/" . $reglink . '">' . url("/preregistration/" . $reglink). '</a>');
    }

    public function dprereg($reference)
    {

        $pr = PreRegModel::where("reference", $reference)->first();
        $pr->status = 0;
        $pr->save();

        $r = RoomModel::find($pr->room_id);
        $r->prereg = "";
        $r->save();

        return back()->with('success', 'Processed successfully. Pre-registration link has been disabled');
    }

    public function preregshow($url)
    {

        $data['preg'] = PreRegModel::where('reference', $url)->first();

        if (!$data['preg']) {
            return redirect()->to("preregistration")
                ->with('error', 'Room url or name does not exist, kindly check your input and try again!');
        }

        $data['u'] = User::find($data['preg']->user_id);

        if ($data['u'] == NULL) {
            return redirect()->to("preregistration")
                ->with('error', 'Room url or name does not exist, kindly check your input and try again!');
        }

        $data['room'] = RoomModel::find($data['preg']->room_id);

        if ($data['room'] == NULL) {
            return redirect()->to("preregistration")
                ->with('error', 'Room url or name does not exist, kindly check your input and try again!');
        }

        $data['faqs'] = Faq::where('status', 1)->get();

        return view('preregistration', $data);

    }

    public function registerprereg(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'ref' => 'required|max:255',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        $data['preg'] = PreRegModel::where('reference', $request->ref)->first();

        if (!$data['preg']) {
            return redirect()->to("preregistration")
                ->with('error', 'An error occured. Kindly contact support.');
        }

        PreRegUserModel::create([
            "prereg_id" => $data['preg']->id,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
        ]);

        $data['room'] = RoomModel::find($data['preg']->room_id);
        $host = User::find($data['room']->user_id);

        session(['roomurl' => $data['room']->url]);
        session(['roomname' => $data['room']->name]);

        $dat['pname']= explode(" ", $request->name)[0];
        $dat['meeting_name']=$data['room']->name;
        $dat['host']=$host->lastname . " ".$host->firstname;
        $dat['date']=$data['preg']->date;
        $dat['time']=$data['preg']->time;
        $dat['timezone']=$data['preg']->timezone;
        $dat['url']= url("/join/" . $data['room']->url);
        $dat['hphone']=$host->phone;
        $dat['hemail']=$host->email;

        Mail::to($request->email)->queue(new PreregParticipantMail($dat));

        return redirect()->route("preregsuccess");
    }

    public function prereParticipants($reference)
    {
        $datas['prereg'] = PreRegModel::where("reference", $reference)->first();
        if ($datas['prereg'] == null) {
            abort(404);
        }

        if ($datas['prereg']->user_id != Auth::id()) {
            abort(404);
        }
        $datas['users'] = PreRegUserModel::where("prereg_id", $datas['prereg']->id)->latest()->get();
        $datas['i'] = 1;
        return view('user.prereg_participants', $datas);
    }

}
