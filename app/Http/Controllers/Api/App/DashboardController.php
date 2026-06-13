<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\InvitesModel;
use App\Models\RoomModel;
use App\Models\Streaming;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $datas['rooms'] = RoomModel::where('user_id', $request->user()->id)->count();
        $datas['upcoming_meetings'] = InvitesModel::where('user_id', $request->user()->id)->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->count();
        $datas['streaming'] = Streaming::where('user_id', $request->user()->id)->count();
        $datas['recordings'] = 0;
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function todayMeeting(Request $request)
    {
        $data = InvitesModel::where('user_id', $request->user()->id)->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $data]);
    }
}
