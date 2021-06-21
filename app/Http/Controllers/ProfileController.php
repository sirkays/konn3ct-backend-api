<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use App\Models\RoomModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $datas['rm'] = RoomModel::where('user_id', Auth::id())->count();
        $datas['p'] = PaymentModel::where('user_id', Auth::id())->count();

        return view('user.profile', $datas);
    }

    public function referee()
    {
        $datas['i'] = 1;
        if(Auth::user()->referral_code == NULL){
            $datas['referee']=[];
            return view('user.referee', $datas);
        }
        $datas['referee'] = User::where('referral', Auth::user()->referral_code)->get();

        return view('user.referee', $datas);
    }
}
