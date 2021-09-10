<?php

namespace App\Http\Controllers;

use App\Mail\EventReminderMail;
use App\Mail\PreregParticipantMail;
use App\Models\Faq;
use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use App\Models\RoomModel;
use App\Models\User;
use Carbon\Carbon;
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
            'reminder' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $reglink = Str::random(20);
        $fName="";

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if (!$file->isValid()) {
                return back()->with('error', 'Invalid file upload');
            }

            if ($file->getClientOriginalExtension() != "png" && $file->getClientOriginalExtension() != "jpg" && $file->getClientOriginalExtension() != "jpeg") {
                return back()->with('error', 'Kindly upload a png/jpg/jpeg file');
            }

            $fName = rand().".png";

            $path = storage_path('prereg/');
            $file->move($path, $fName);

            echo "Uploaded successfully";
        }


        $pd=date_parse($input['date']);
        print_r($pd);

        $rd=$pd['year']."-".$pd['month']."-".$pd['day'];

        echo $rd;

        $date=date_create($rd);
        date_sub($date,date_interval_create_from_date_string($input['reminder']." days"));
        $reminder= date_format($date,"Y-m-d");

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
            "logo" => $fName,
            "reminder" => $reminder,
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

        if ($data['preg']->status != 1) {
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

    public function checkReminder(){
        echo "Starting check event Reminder";
        echo "\n";

        $preg_lists = PreRegModel::where('status', 1)->get();

        foreach ($preg_lists as $preg_list){
            echo "Working on event - " .$preg_list->title;
            echo "\n";
            echo "Reminder Date: " . $preg_list->reminder;
            echo "Current Date: " . Carbon::now()->format("Y-m-d");
            echo "\n";

            if($preg_list->reminder == Carbon::now()->format("Y-m-d")){
                echo "I need to send reminder to users";
                echo "\n";
                $users = PreRegUserModel::where("prereg_id", $preg_list->id)->get();
                $host = User::find($preg_list->user_id);

                foreach ($users as $user){
                    echo "Working on user - " .$user->name;
                    echo "\n";
                    $dat['pname']=explode(" ", $user->name)[0];
                    $dat['event_name']=$preg_list->title;
                    $dat['host']=$host->lastname . " ".$host->firstname;
                    $dat['formatted_date']=Carbon::parse($preg_list->date)->toFormattedDateString();
                    $dat['formatted_time']=Carbon::parse($preg_list->time)->toTimeString();
                    $dat['date']=$preg_list->date;
                    $dat['time']=$preg_list->time;
                    $dat['timezone']=$preg_list->timezone;
                    $dat['url']= url("/join/" . $preg_list->url);
                    $dat['hphone']=$host->phone;
                    $dat['hemail']=$host->email;

                    echo "Sending event reminder to ".$user->email;
                    Mail::to($user->email)->send(new EventReminderMail($dat));
                }

            }
        }
    }

}
