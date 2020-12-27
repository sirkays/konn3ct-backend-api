<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingsModel;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class RoomsController extends Controller
{
    public function show(){

        $datas['roomys']=RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id','room.user_id')
            ->select('room.*', 'users.firstname as firstname', 'users.lastname as lastname')
            ->get();
        $datas['rooms']=RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id','room.user_id')
            ->select('room.*', 'users.firstname as firstname', 'users.lastname as lastname')
            ->get();
        $datas['roomstc']=RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id','room.user_id')
            ->count();

        $datas['active']=0;

        if (!App::environment(['local', 'staging'])) {
            foreach ($datas['roomys'] as $i) {
                $ms = \Bigbluebutton::isMeetingRunning($i->id);
                if ($ms) {
                    $datas['active']++;
                }
            }
        }

        return view('admin.rooms', $datas);
    }

    public function meetings(){
        $datas['meetings']=MeetingsModel::orderBy('id', 'desc')
            ->join('room','room.id','=','meetings.meeting_id')
            ->where('meetings.status','=','start meeting')
            ->select('meetings.*', 'room.url as room_url', 'room.name as room_name')
            ->paginate(10);
        $datas['meetingstc']=MeetingsModel::join('room','room.id','=','meetings.meeting_id')
            ->where('meetings.status','=','start meeting')
            ->count();
        $datas['meetingsdc']=MeetingsModel::distinct('email')->count();
        return view('admin.meetings', $datas);
    }

    public function meetingsd($id){
//        $fm=MeetingsModel::where('id','=',$id)->orderBy('id','desc')->first();

        $datas['meetings']=MeetingsModel::orderBy('id', 'asc')
            ->join('room','room.id','=','meetings.meeting_id')
            ->where('meetings.identifier','=',$id)
            ->select('meetings.*', 'room.url as room_url', 'room.name as room_name')
            ->paginate(10);
        $datas['meetingstc']=MeetingsModel::join('room','room.id','=','meetings.meeting_id')
            ->where('meetings.identifier','=',$id)
            ->count();
        $datas['meetingsdc']=MeetingsModel::distinct('email')->count();
        return view('admin.meetingsd', $datas);
    }
}
