<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\InvitesModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{

    public function upcomingMeetings()
    {
        $datas = InvitesModel::orderBy('id', 'desc')
            ->where('user_id', '=', Auth::id())
            ->whereDate('date', '>=', Carbon::now())
            ->simplePaginate(5);

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }
}
