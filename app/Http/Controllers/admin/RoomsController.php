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

        $datas['rooms']=RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id','room.user_id')
            ->select('room.*', 'users.firstname as firstname', 'users.lastname as lastname')
            ->get();

        $datas['active']=0;

        if (!App::environment(['local', 'staging'])) {
            foreach ($datas['rooms'] as $i) {
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
            ->join('room','room.id','=','meetings.id')
            ->select('meetings.*', 'room.url as room_url', 'room.name as room_name')
            ->get();
        $datas['meetingstc']=MeetingsModel::count();
        $datas['meetingsdc']=MeetingsModel::distinct('email')->count();
        return view('admin.meetings', $datas);
    }
}
