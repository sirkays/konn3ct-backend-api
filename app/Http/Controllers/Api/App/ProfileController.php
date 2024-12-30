<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\PaymentModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $datas['rooms_count'] = RoomModel::where('user_id', Auth::id())->count();
        $datas['payments_count'] = PaymentModel::where('user_id', Auth::id())->count();
        $datas['user_plan'] = PlanModel::find(Auth::user()->plan);
        $datas['user'] = Auth::user();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

}
