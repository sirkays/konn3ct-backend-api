<?php

namespace App\Http\Controllers\Api\Integration;

use App\Http\Controllers\Controller;
use App\Models\PlanModel;

class PlansController extends Controller
{
    public function planList()
    {
        $datas = PlanModel::get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

}
