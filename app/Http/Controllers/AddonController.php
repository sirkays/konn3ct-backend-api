<?php

namespace App\Http\Controllers;

use App\Models\AddonModel;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function show(){
        $data['addons']=AddonModel::get();

        $data['i']=1;

        return view('user.addons', $data);
    }
}
