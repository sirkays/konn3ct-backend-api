<?php

namespace App\Http\Controllers;

use App\Models\DonationPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VulteHookController extends Controller
{
    public function index(Request $request){
        $input = $request->all();

        Log::info("VulteHookController:".json_encode($input));

        if($input['service'] != "wallet"){
            return "ok";
        }

        if($input['data']['status'] != "successful"){
            return "ok";
        }

        if(isset($input['data']['metadata']['pay_type'])  && $input['data']['metadata']['pay_type'] == "donation"){
            $dp=DonationPayment::where([["id",$input['data']['metadata']['payment_id']], ["status",0]])->first();

            if($dp){
                $dp->status=1;
                $dp->paid_at=Carbon::now();
                $dp->notification_response=json_encode($input);
                $dp->save();

                return "Payment Successful";
            }
        }

        return "Noted";
    }

    public function bankTransfer(Request $request){
        $input = $request->all();

        Log::info("Bank Transfer VulteHookController:".json_encode($input));

        if(!isset($input['details'])){
            return "Not allowed";
        }

        if($input['details']['status'] != "Successful"){
            return "ok";
        }

        if(isset($input['details']['meta']['pay_type'])  && $input['details']['meta']['pay_type'] == "donation"){
            $dp=DonationPayment::where([["id",$input['details']['meta']['payment_id']], ["status",0]])->first();

            if($dp){
                $dp->status=1;
                $dp->amount=$input['details']['amount']/100;
                $dp->paid_at=Carbon::now();
                $dp->notification_response=json_encode($input);
                $dp->save();

                return "Payment Successful";
            }
        }

        return "Noted";
    }
}
