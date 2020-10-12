<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(){
        $datas['rm']=RoomModel::where('user_id',Auth::id())->count();
        $datas['p']=PaymentModel::where('user_id',Auth::id())->count();

        return view('user.profile', $datas);
    }
}
