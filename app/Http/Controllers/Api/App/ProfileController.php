<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\PaymentModel;
use App\Models\PlanModel;
use App\Models\PlanPricing;
use App\Models\RoomModel;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function payments(Request $request)
    {
        $datas['payments'] = PaymentModel::where('user_id', Auth::id())->with('planDetails')->OrderBy('id', 'desc')->simplePaginate(10);
        $datas['sumed_payments'] = PaymentModel::where('user_id', Auth::id())->sum('amount');
        $datas['count_payments'] = PaymentModel::where('user_id', Auth::id())->count();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function plans(Request $request)
    {
        $input = $request->all();
        $vs = $input['vs'];

        if ($vs['countryCode'] == null) {
            $country = "NG";
        } else {
            $country = $vs['countryCode'];
        }

        if ($country == "IN") {
            $lite_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "INR"], ["plan_id", 2]])->first();

            $lite_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "INR"], ["plan_id", 2]])->first();

            $pro_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "INR"], ["plan_id", 3]])->first();

            $pro_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "INR"], ["plan_id", 3]])->first();


            $datas['lite_monthly'] = "INR $lite_monthly1->price";
            $datas['lite_yearly'] = "INR $lite_yearly1->price";
            $datas['pro_monthly'] = "INR $pro_monthly1->price";
            $datas['pro_yearly'] = "INR $pro_yearly1->price";

        } else {

            $lite_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "NGN"], ["plan_id", 2]])->first();
            $lite_monthly2 = PlanPricing::where([["type", "monthly"], ["currency", "USD"], ["plan_id", 2]])->first();

            $lite_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "NGN"], ["plan_id", 2]])->first();
            $lite_yearly2 = PlanPricing::where([["type", "yearly"], ["currency", "USD"], ["plan_id", 2]])->first();

            $pro_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "NGN"], ["plan_id", 3]])->first();
            $pro_monthly2 = PlanPricing::where([["type", "monthly"], ["currency", "USD"], ["plan_id", 3]])->first();

            $pro_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "NGN"], ["plan_id", 3]])->first();
            $pro_yearly2 = PlanPricing::where([["type", "yearly"], ["currency", "USD"], ["plan_id", 3]])->first();

            $datas['lite_monthly'] = "$$lite_monthly2->price / #$lite_monthly1->price";
            $datas['lite_yearly'] = "$$lite_yearly2->price / #$lite_yearly1->price";
            $datas['pro_monthly'] = "$$pro_monthly2->price / #$pro_monthly1->price";
            $datas['pro_yearly'] = "$$pro_yearly2->price / #$pro_yearly1->price";
        }


        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function referee()
    {
        if(Auth::user()->referral_code == NULL){
            return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => []]);
        }

        $datas = User::where('referral', Auth::user()->referral_code)->select('firstname','lastname','email','created_at')->simplePaginate(10);

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }


}
