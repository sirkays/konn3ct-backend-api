<?php

namespace App\Http\Controllers\Api\Integration;

use App\Http\Controllers\Controller;
use App\Models\MeetingsModel;

class MeetingController extends Controller
{
    public function meetingList()
    {
        $datas = MeetingsModel::get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }
}
