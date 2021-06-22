<?php

namespace App\Http\Controllers;

use App\Models\InvitesModel;
use Illuminate\Support\Facades\Auth;

class OtherController extends Controller
{
    public function invites(){
        $data['invites'] = InvitesModel::where('user_id', Auth::id())->get();
        $data['i'] = 1;

        return view('user.invites', $data);
    }
}
