<?php

namespace App\Http\Controllers;

use App\Models\SettingsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyAuthController extends Controller
{
    public function register($id){
        if(Auth::user()){
            return redirect()->to("/changeplan/".$id);
        }
        // Via a request instance...
        // Via the global helper...
        session(['plan' => $id]);
        $set=SettingsModel::first();
        $freetrial=$set->freetrial_status;
        $freetrial_days=$set->freetrial_days;
        return view('auth.register', ['freetrial'=>$freetrial, 'freetrial_days'=>$freetrial_days]);
    }
}
