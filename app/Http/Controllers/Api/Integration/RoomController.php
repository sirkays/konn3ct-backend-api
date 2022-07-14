<?php

namespace App\Http\Controllers\Api\Integration;

use App\Http\Controllers\Controller;
use App\Models\RoomModel;

class RoomController extends Controller
{
    public function roomList()
    {
        $datas = RoomModel::with('owner')->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }
}
