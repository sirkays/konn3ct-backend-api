<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Jobs\CreateBGAccountJob;
use App\Jobs\KCEnrollParticipantJob;
use App\Jobs\SendEventReminderJob;
use App\Jobs\WhatsappInviteJob;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PreregistrationController extends Controller
{
    
    public function preregListSummary(Request $request)
    {
        // Get all the user's events
        $userEvents = PreRegModel::where("user_id", Auth::id())->pluck('id');

        // Active Events: event date greater than today
        $activeEvents = PreRegModel::where([
            "user_id" => Auth::id(),
        ])->where('date', '>', Carbon::today()->toDateString())->count();

        // Total Registrations: across all events for this user
        $totalRegistrations = PreRegUserModel::whereIn('prereg_id', $userEvents)->count();

        // Revenue (30d): participants who have paid in last 30 days (use 'paid_at' or 'paid' column)
        $revenue30d = PreRegUserModel::whereIn('prereg_id', $userEvents)
            ->where('paid', 1)
            ->where(function($query) {
                $query->where('paid_at', '>=', Carbon::now()->subDays(30))
                      ->orWhere('created_at', '>=', Carbon::now()->subDays(30));
            })
            ->count();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 
        'data' => [
            'active_events' => $activeEvents,
            'total_registrations' => $totalRegistrations,
            'revenue_30d_participants' => $revenue30d
        ],
        ]);
    }

    public function preregList(Request $request)
    {
        $datas = PreRegModel::where([
            "user_id" => Auth::id(),
        ])->latest()->simplepaginate(10);

        // For each event, calculate registrations and revenue
        $datas->getCollection()->transform(function($event) {
            // Count total registrations for this event
            $event->total_registrations = PreRegUserModel::where('prereg_id', $event->id)->count();
            
            // Count paid registrations for this event (use 'paid' column)
            $paidUsers = PreRegUserModel::where('prereg_id', $event->id)
                ->where('paid', 1)
                ->get();
            
            $event->paid_registrations = $paidUsers->count();
            
            // Calculate total revenue for this event
            if ($event->free == 0) {
                // Sum the 'amount' column from paid users for accuracy
                $event->total_revenue = $paidUsers->sum('amount');
               
            } else {
                $event->total_revenue = 0;
            }

             $event->revenue_currency = $event->currency;

            return $event;
        });


        return response()->json([
            'success' => true, 
            'message' => 'Fetched successfully',
            'data' => $datas
        ]);
    }

    public function recentRegistration(Request $request)
    {
        // Get all the user's events
        $userEvents = PreRegModel::where("user_id", Auth::id())->latest()->pluck('id');

        // Total Registrations: across all events for this user
        $RecentRegistrations = PreRegUserModel::whereIn('prereg_id', $userEvents)->with('preReg')->latest()->limit(10)->get();

        return response()->json([
            'success' => true, 
            'message' => 'Fetched successfully', 
            'data' => $RecentRegistrations
        ]);
    }

    public function prereParticipants($reference)
    {
        $datas['prereg'] = PreRegModel::where([["reference", $reference], ['user_id',Auth::id()]])->first();
        if ($datas['prereg'] == null) {
            return response()->json(['success' => false, 'message' => 'Event not found']);
        }

        $datas['revenue'] = PreRegUserModel::where('prereg_id', $datas['prereg']->id)
            ->where('paid', 1)
            ->count();

        $datas['users'] = PreRegUserModel::where("prereg_id", $datas['prereg']->id)->latest()->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function prereg(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'hostname' => 'required',
            'room_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'timezone' => 'required',
            'about' => 'required',
            'reminder' => 'required',
            'public' => 'sometimes|numeric|in:0,1',
            'free' => 'sometimes|numeric|in:0,1',
            'currency' => 'sometimes|string|in:NGN,USD',
            'amount' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $r = RoomModel::where([["id",$input['room_id']], ['user_id',Auth::id()]])->first();

        if(!$r){
            return response()->json(['success' => false, 'message' => "Invalid Room supplied"]);
        }

        $reglink = Str::random(20);
        $fName="";

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if (!$file->isValid()) {
                return response()->json(['success' => false, 'message' => "Invalid file upload"]);
            }

            if ($file->getClientOriginalExtension() != "png" && $file->getClientOriginalExtension() != "jpg" && $file->getClientOriginalExtension() != "jpeg") {
                return response()->json(['success' => false, 'message' => "Kindly upload a png/jpg/jpeg file"]);
            }

            $fName = rand().".png";

            $path = storage_path('prereg/');
            $file->move($path, $fName);

//            echo "Uploaded successfully";
        }

        $pd=date_parse($input['date']);
//        print_r($pd);

        $rd=$pd['year']."-".$pd['month']."-".$pd['day'];

//        echo $rd;

        $date=date_create($rd);
        date_sub($date,date_interval_create_from_date_string($input['reminder']." days"));
        $reminder= date_format($date,"Y-m-d");

        if(isset($input['free']) && $input['free'] == 0){
            if(!isset($input['currency']) || !isset($input['amount'])){
                return response()->json(['success' => false, 'message' => "Amount and currency are required for paid events"]);
            }

            if($input['amount'] < 1){
                return response()->json(['success' => false, 'message' => "Valid amount is required for Paid Events"]);
            }
        }

        PreRegModel::create([
            "user_id" => Auth::id(),
            "room_id" => $input['room_id'],
            "reference" => $reglink,
            "title" => $input['title'],
            "host_name" => $input['hostname'],
            "date" => $input['date'],
            "time" => $input['time'],
            "timezone" => $input['timezone'],
            "about" => $input['about'],
            "logo" => $fName,
            "reminder" => $reminder,
            "tags" => $input['tags'] ?? "",
            "public" => $input['public'] ?? 1,
            "free" => $input['free'] ?? 1,
            "currency" => $input['currency'] ?? "NGN",
            "amount" => $input['amount'] ?? "0",
        ]);

        $r->prereg = $reglink;
        $r->save();

        return response()->json(['success' => true, 'message' => 'Processed successfully', 'data' => "https://www.konn3ct.com/event/" . $reglink ]);
    }

    public function preregModify(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'id' => 'required|max:255',
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

        $pd = date_parse($input['date']);
        print_r($pd);

        $rd = $pd['year'] . "-" . $pd['month'] . "-" . $pd['day'];

        echo $rd;

        $date = date_create($rd);
        date_sub($date, date_interval_create_from_date_string($input['reminder'] . " days"));
        $reminder = date_format($date, "Y-m-d");

        $prereg = PreRegModel::find($input['id']);

        $fName = $prereg->logo;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if (!$file->isValid()) {
                return back()->with('error', 'Invalid file upload');
            }

            if ($file->getClientOriginalExtension() != "png" && $file->getClientOriginalExtension() != "jpg" && $file->getClientOriginalExtension() != "jpeg") {
                return back()->with('error', 'Kindly upload a png/jpg/jpeg file');
            }

            $fName = rand() . ".png";

            $path = storage_path('prereg/');
            $file->move($path, $fName);

            echo "Uploaded successfully";
        }

        $prereg->title = $input['title'];
        $prereg->host_name = $input['hostname'];
        $prereg->date = $input['date'];
        $prereg->time = $input['time'];
        $prereg->timezone = $input['timezone'];
        $prereg->about = $input['about'];
        $prereg->logo = $fName;
        $prereg->reminder = $reminder;
        $prereg->save();

        $reglink = $prereg->reference;

        return back()->with('success', 'Modified successfully. Your Pre-registration link is <a href="' . url("/preregistration/") . "/" . $reglink . '">' . url("/preregistration/" . $reglink) . '</a>');
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

    public function preregshow($reference)
    {

        $data['preg'] = PreRegModel::where('reference', $reference)->with('owner')->get()->makeHidden('reference','reminder','updated_at','id','user_id','room_id');

        if (count($data['preg']) == 0) {
            return response()->json(['success' => false, 'message' => 'Invalid Reference!']);
        }

        if ($data['preg'][0]->status != 1) {
            return response()->json(['success' => false, 'message' => 'The Event has ended']);
        }

        $data['faqs'] = Faq::where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $data]);
    }
    public function preregshowSearch(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'search' => 'required|max:200'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $data = PreRegModel::where([['title', 'LIKE','%'.$input['search'].'%'], ['public','=',1]])->orwhere([['host_name', 'LIKE','%'.$input['search'].'%'], ['public','=',1]])->select('host_name','reference','title','date','time','timezone','logo')->latest()->limit(20)->get();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $data]);
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
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }


        $data['preg'] = PreRegModel::where('reference', $request->ref)->first();

        if (!$data['preg']) {
            return response()->json(['success' => false, 'message' => 'Invalid reference provided']);
        }

        $check = PreRegUserModel::where(["prereg_id" => $data['preg']->id, "email" => $request->email])->first();

        if ($check) {
            return response()->json(['success' => false, 'message' => 'You have register for this event already.']);
        }

        $prum=PreRegUserModel::create([
            "prereg_id" => $data['preg']->id,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
        ]);

        if($data['preg']->free == 0){

            $reff=$data['preg']->reference.'-'.$prum->id;

            $payload='{
            "amount": '.$data['preg']->amount.',
            "walletId": "master",
            "currency": "'.$data['preg']->currency.'",
            "metadata": {
                "pay_type": "event",
                "payment_id": "'.$reff.'",
                "payee_id": "'.$prum->id.'",
                "payee_name": "'.$input['name'].'",
                "payee_email":"'.$input['email'].'"
                    }
            }';

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => env('VULTE_BASEURL').'/v1/checkout/initialize',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_POSTFIELDS =>$payload,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: '.env('VULTE_KEY')
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            Log::info("Card Event Payment: Payload $payload; Response $response");

            if($response == null){
                return response()->json(['success' => false, 'message' => 'Unable to process payment']);
            }

            $rep=json_decode($response, true);

            $prum->payment_reference = $reff;
            $prum->payment_provider = "vulte";
            $prum->payment_provider_response = json_encode($response);
            $prum->save();

            if($rep['success']){
                return response()->json(['success' => true, 'message' => 'Proceed to Payment', 'free'=>$data['preg']->free, 'type'=>'card', 'data'=>$rep['data']['authorization_url'], 'reference'=>$reff]);
            }else{
                $prum->delete();
                return response()->json(['success' => false, 'message' => 'Unable to make payment at this time. Try again']);
            }
        }

        $data['room'] = RoomModel::find($data['preg']->room_id);

        if(!$data['room']){
            return response()->json(['success' => false, 'message' => 'Unable to complete process']);
        }

        $host = User::find($data['room']->user_id);

        $dat['pname'] = explode(" ", $request->name)[0];
        $dat['meeting_name'] = $data['room']->name;
        $dat['host'] = $host->lastname . " " . $host->firstname;
        $dat['date'] = $data['preg']->date;
        $dat['time'] = $data['preg']->time;
        $dat['timezone'] = $data['preg']->timezone;
        $dat['url'] = url("/join/" . $data['room']->url);
        $dat['hphone'] = $host->phone;
        $dat['hemail'] = $host->email;
        $dat['event_name'] = $data['preg']->title;

        $jobi['name'] = $request->name;
        $jobi['email'] = $request->email;
        $jobi['phone'] = $request->phone ?? '';

        CreateBGAccountJob::dispatch($jobi)->delay(now()->addSecond());
        KCEnrollParticipantJob::dispatch($data['preg']->room_id, $request->email)->delay(now()->addMinutes(2));

        Mail::to($request->email)->queue(new PreregParticipantMail($dat));

        return response()->json(['success' => true, 'message' => 'Registered Successfully', 'free'=>$data['preg']->free]);
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
                $room = RoomModel::find($preg_list->room_id);

                foreach ($users as $user){
                    echo "Working on user - " .$user->name;
                    echo "\n";
                    $dat['pname']=explode(" ", $user->name)[0];
                    $dat['event_name'] = $preg_list->title;
                    $dat['host'] = $host->lastname . " " . $host->firstname;
                    $dat['formatted_date'] = Carbon::parse($preg_list->date)->toFormattedDateString();
                    $dat['formatted_time'] = Carbon::parse($preg_list->time)->toTimeString();
                    $dat['date'] = $preg_list->date;
                    $dat['time'] = $preg_list->time;
                    $dat['timezone'] = $preg_list->timezone;
                    $dat['url'] = url("/join/" . $preg_list->url);
                    $dat['hphone'] = $host->phone;
                    $dat['hemail'] = $host->email;


                    $input['hostname'] = $dat['host'];

                    $input['roomlink'] = $dat['url'];

                    $input['accesscode'] = "<<hidden";

                    $input['title'] = $dat['event_name'];

                    $input['date'] = $dat['date'];

                    $input['time'] = $dat['time'];

                    $input['roomname'] = $room->name;

                    $input['timezone'] = $dat['timezone'];

                    $input['guest'] = $user->phone;

                    WhatsappInviteJob::dispatch($input)->delay(now()->addSeconds(5));

                    echo "Sending event reminder to " . $user->email;
                    Mail::to($user->email)->send(new EventReminderMail($dat));
                }

            }
        }
    }

    public function sendReminder($reference)
    {

        $datas['prereg'] = PreRegModel::where("reference", $reference)->first();
        if ($datas['prereg'] == null) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }

        if ($datas['prereg']->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }

        SendEventReminderJob::dispatch($datas['prereg']);

        return response()->json(['success' => true, 'message' => 'Reminder will be sent out in some minutes time']);
    }

}
