<?php

namespace App\Http\Controllers\Api\Integration;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function userList()
    {
        $datas = User::get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function userListByPlan($plan)
    {
        $datas = User::where("plan", $plan)->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }
}
