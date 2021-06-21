<?php

namespace App\Http\Controllers;

use App\Models\InvitesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherController extends Controller
{
    public function invites(){
        $data['invites']=InvitesModel::where('user_id', Auth::id())->get();

        return view('user.invites', $data);
    }
}
