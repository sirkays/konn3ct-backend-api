<?php

namespace App\Http\Controllers;

use App\Models\SettingsModel;
use Illuminate\Http\Request;

class MyAuthController extends Controller
{
    public function register($id){
        // Via a request instance...
        // Via the global helper...
        session(['plan' => $id]);
        $set=SettingsModel::first();
        $freetrial=$set->freetrial_status;
        $freetrial_days=$set->freetrial_days;
        return view('auth.register', ['freetrial'=>$freetrial, 'freetrial_days'=>$freetrial_days]);
    }
}
