<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingsModel;
use App\Models\PaymentModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UsersController extends Controller
{
    public function show(){

        $datas['users']=User::orderBy('id', 'desc')->get();
        $datas['userstc']=User::count();
        $datas['i']=1;

        return view('admin.users', $datas);
    }

    public function showUser($id){

        $datas['user']=User::find($id);
        $datas['plans']=PlanModel::get();
        if(!$datas['user']){
            return back()->with("error", "User does not exist");
        }

        if($datas['user']->referral != "") {
            $datas['referred'] = User::where('referral_code', '=', $datas['user']->referral)->first();
            if ($datas['referred']) {
                $datas['referredby'] = $datas['referred']->firstname . " " . $datas['referred']->lastname;
            }
        }else{
            $datas['referredby']="";
        }

        $datas['rooms']=RoomModel::where('user_id',$id)->get();
        $datas['payments']=PaymentModel::join('plans','plans.id','=','payment.plan')->where('payment.user_id', $id)->select('payment.*', 'plans.name as plan')->OrderBy('id', 'desc')->limit(1)->get();
        $datas['meetings']=MeetingsModel::join('room','room.id','=','meetings.meeting_id')->where('meetings.email',$datas['user']->email)->select('room.name as roomname', 'meetings.*')->get();

        $datas['rm']=RoomModel::where('user_id',$id)->count();
        $datas['p']=PaymentModel::where('user_id',$id)->count();

        $r2=RoomModel::where('user_id', $id)->select('id')->get();
        $rc=RoomModel::where('user_id', $id)->select('id')->count();

        $r=RoomModel::where('user_id', $id)->select('id')->first();
        $datas['recordings']=[];
        $datas['i']=1;

        if ($rc==1){
            if (App::environment(['local', 'staging'])) {
                $datas['record']='[{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604321641383","endTime":"1604322318372","participants":"2","rawSize":"4549560","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"4158906","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","processingTime":"178453","length":"10","size":"4158906","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png"]}}}},"data":[]},{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604320993517","endTime":"1604321453617","participants":"2","rawSize":"3500466","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"2074489","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","processingTime":"80959","length":"4","size":"2074489","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-3.png"]}}}},"data":[]}]';
            }else {
                $datas['record'] = Bigbluebutton::getRecordings([
                    'meetingID' => $r->id,
                ]);
            }
            $datas['recordings']=json_decode($datas['record'], true);

        }else {
            $er = "";
            foreach ($r2 as $r) {
                $er = $er . $r->id . ",";
            }
            $fer = "[" . $er . "]";

            if (App::environment(['local', 'staging'])) {
                $datas['record'] = '[{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604321641383","endTime":"1604322318372","participants":"2","rawSize":"4549560","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"4158906","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","processingTime":"178453","length":"10","size":"4158906","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png"]}}}},"data":[]},{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604320993517","endTime":"1604321453617","participants":"2","rawSize":"3500466","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"2074489","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","processingTime":"80959","length":"4","size":"2074489","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-3.png"]}}}},"data":[]}]';
            } else {

                $datas['record'] = Bigbluebutton::getRecordings([
                    'meetingID' => $fer, //pass as array if get multiple recordings
                ]);
            }

            $datas['recordings'] = json_decode($datas['record'], true);
        }

//
//            $er="";
//        foreach ($r2 as $r){
//            $er=$er.$r->id.",";
//        }
//        $fer="[".$er."]";
//
//        if (App::environment(['local', 'staging'])) {
//            $datas['record']='[{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604321641383","endTime":"1604322318372","participants":"2","rawSize":"4549560","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"4158906","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383","processingTime":"178453","length":"10","size":"4158906","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604321641383\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604321641416\/thumbnails\/thumb-3.png"]}}}},"data":[]},{"recordID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","meetingID":"23","internalMeetingID":"d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","name":"Sammy","isBreakout":"false","published":"true","state":"published","startTime":"1604320993517","endTime":"1604321453617","participants":"2","rawSize":"3500466","metadata":{"endcallbackurl":"https:\/\/dev.konn3ct.net\/leftsession","isBreakout":"false","meetingId":"23","meetingName":"Sammy"},"size":"2074489","playback":{"format":{"type":"presentation","url":"https:\/\/dev.konn3ct.net\/playback\/presentation\/2.0\/playback.html?meetingId=d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517","processingTime":"80959","length":"4","size":"2074489","preview":{"images":{"image":["https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-1.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-2.png","https:\/\/dev.konn3ct.net\/presentation\/d435a6cdd786300dff204ee7c2ef942d3e9034e2-1604320993517\/presentation\/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1604320993542\/thumbnails\/thumb-3.png"]}}}},"data":[]}]';
//        }else {
//            $datas['record'] = \Bigbluebutton::getRecordings([
//                'meetingID' => $fer,
//            ]);
//        }
//
//        $datas['recordings']=json_decode($datas['record'], true);

        return view('admin.user', $datas);
    }

    public function upgradeplan(Request $request){
        $input=$request->all();
        $user=User::find($input['user']);
        if($input['plan']!=1){

            if($input['plan']==$user->plan){
                if (Carbon::now()->diffInDays(Carbon::parse($user->subscription), false) < 0) {
                    $subd=Carbon::now()->addMonths($input['duration']);
                }else{
                    $subd = Carbon::parse($user->subscription)->addMonths($input['duration']);
                }
            }else{
                $subd = Carbon::now()->addMonths($input['duration']);
            }
            User::where('id', $input['user'])->update(['subscription' => $subd, 'plan' => $input['plan'], 'status' => 'active']);
        } else {
            User::where('id', $input['user'])->update(['plan' => $input['plan'], 'status' => 'active']);
        }

        return back()->with("success", "User subscription modified successfully");

    }

    public function referrals()
    {
        $datas['referee'] = User::join('users as frnd', 'frnd.referral_code', '=', 'users.referral')->where('users.referral', '!=', NULL)->get();
        $datas['i'] = 1;

        return view('admin.referrals', $datas);
    }

}
