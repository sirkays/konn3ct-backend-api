<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Reseller;
use App\Models\User;

class ResellerController extends Controller
{
    function list()
    {
        $datas['datas'] = Reseller::get();
        return view('admin.resellers', $datas);
    }

    function listUsers($id)
    {
        $lu = Reseller::find($id);
        if (!$lu) {
            abort(404);
        }

        $datas['userstc'] = User::where('reseller_id', $id)->count();
        $datas['i'] = 1;

        $datas['users'] = User::where('reseller_id', $id)->get();
        return view('admin.resellers_users', $datas);
    }
}
