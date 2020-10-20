<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(){

        $datas['users']=User::orderBy('id', 'desc')->get();
        $datas['userstc']=User::count();
        return view('admin.users', $datas);
    }
}
