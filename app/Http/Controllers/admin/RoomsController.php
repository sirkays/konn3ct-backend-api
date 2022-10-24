<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingsModel;
use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use App\Models\RoomModel;
use Carbon\Carbon;

class RoomsController extends Controller
{
    public function dashboard()
    {

        $datas['roomstc'] = RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id', 'room.user_id')
            ->count();

        $datas['active'] = 0;

        $datas['i'] = 1;

        return view('admin.dashboard', $datas);
    }

    public function show()
    {

        $datas['roomys'] = RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id', 'room.user_id')
            ->select('room.*', 'users.firstname as firstname', 'users.lastname as lastname')
            ->get();
        $datas['rooms'] = RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id', 'room.user_id')
            ->select('room.*', 'users.firstname as firstname', 'users.lastname as lastname')
            ->get();
        $datas['roomstc']=RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id','room.user_id')
            ->count();

        $datas['active']=0;

//        try {
//            if (!App::environment(['local', 'staging'])) {
//                foreach ($datas['roomys'] as $i) {
//                    $ms = \Bigbluebutton::isMeetingRunning($i->id);
//                    if ($ms) {
//                        $datas['active']++;
//                    }
//                }
//            }
//        }catch (Exception $e){
//            echo "skipping error";
//        }

        $datas['i']=1;

        return view('admin.rooms', $datas);
    }

    public function meetings()
    {
        $datas['meetings'] = MeetingsModel::orderBy('id', 'desc')
            ->join('room', 'room.id', '=', 'meetings.meeting_id')
            ->where('meetings.status', '=', 'start meeting')
            ->select('meetings.*', 'room.url as room_url', 'room.name as room_name')
            ->get();
        $datas['meetingstc'] = MeetingsModel::join('room', 'room.id', '=', 'meetings.meeting_id')
            ->where('meetings.status', '=', 'start meeting')
            ->where('meetings.created_at', 'LIKE', '%' . Carbon::now()->format('Y-m-d') . '%')
            ->count();
        $datas['meetingstc'] = MeetingsModel::join('room', 'room.id', '=', 'meetings.meeting_id')
            ->where('meetings.status', '=', 'start meeting')
            ->count();
        $datas['meetings_today'] = MeetingsModel::where([['status', '=', 'start meeting'], ['created_at', 'LIKE', Carbon::now()->format('Y-m-d') . '%']])->count();
        $datas['meetings_yesterday'] = MeetingsModel::where([['status', '=', 'start meeting'], ['created_at', 'LIKE', Carbon::now()->subDay()->format('Y-m-d') . '%']])->count();
        $datas['meetings_month'] = MeetingsModel::where([['status', '=', 'start meeting'], ['created_at', 'LIKE', Carbon::now()->format('Y-m') . '%']])->count();

        $datas['meetingsdc'] = MeetingsModel::distinct('email')->count();
        $datas['i'] = 1;
        return view('admin.meetings', $datas);
    }

    public function meetingsd($id)
    {

        $datas['meetings'] = MeetingsModel::orderBy('id', 'desc')
            ->join('room', 'room.id', '=', 'meetings.meeting_id')
            ->where('meetings.identifier', '=', $id)
            ->select('meetings.*', 'room.url as room_url', 'room.name as room_name')
            ->get();
        $datas['meetingstc'] = MeetingsModel::join('room', 'room.id', '=', 'meetings.meeting_id')
            ->where('meetings.identifier', '=', $id)
            ->count();
        $datas['meetingsdc'] = MeetingsModel::distinct('email')->count();
        $datas['i'] = 1;
        return view('admin.meetingsd', $datas);
    }


    public function prereg()
    {

        $datas['preregs'] = PreRegModel::with('room', 'owner')->orderBy('id', 'desc')->paginate(20);

        $datas['roomstc'] = PreRegModel::count();

        $datas['active'] = 0;


        $datas['i'] = 1;

        return view('admin.preregs', $datas);
    }

    public function preregsDetails($id)
    {
        $datas['preregs'] = PreRegModel::with('room', 'owner')->find($id);

        $datas['users'] = PreRegUserModel::where("prereg_id", $id)->get();

        $datas['users_count'] = PreRegUserModel::where("prereg_id", $id)->count();

        $datas['i'] = 1;

        return view('admin.preregs_users', $datas);
    }

}
