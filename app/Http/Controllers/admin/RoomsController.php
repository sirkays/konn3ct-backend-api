<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomsController extends Controller
{
    public function show(){

        $datas['rooms']=RoomModel::orderBy('id', 'desc')
            ->join('users', 'users.id','room.user_id')
            ->select('room.*', 'users.name as user_name')
            ->get();
        $datas['roomstc']=RoomModel::count();
        return view('admin.rooms', $datas);
    }
}
