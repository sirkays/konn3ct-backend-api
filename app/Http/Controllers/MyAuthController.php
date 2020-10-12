<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyAuthController extends Controller
{
    public function register($id){
        // Via a request instance...
        // Via the global helper...
        session(['plan' => $id]);
        return view('auth.register');
    }
}
