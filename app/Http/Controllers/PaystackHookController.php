<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaystackHookController extends Controller
{
    public function index(Request $request){
        $input = $request->all();

        $data2= json_encode($input);

        echo "52.31.139.75, 52.49.173.169, 52.214.14.220<br/>";

        DB::table('paystackhook')->insert(['data'=> $data2]);

        // only a post with paystack signature header gets our attention
        if (!$request->headers->has('X-Paystack-Signature')) {
            return "invalid request";
        }

        if($input['event']!="charge.success"){
            return "charge->success expected";
        }
        $domain=$input['data']['domain'];
        $status=$input['data']['status'];
        $reference=$input['data']['reference'];
        $amount=$input['data']['amount']/100;
        $fees=$input['data']['fees']/100;

        if($domain!="live"){
            return "demo env";
        }

        if($status!="success"){
            return "Success status expected";
        }

//
//        $tra=PaymentModel::where('reference',$reference)->first();
//        if(!$tra){
//            if($input['data']['status']=="success"){
//                $user=User::where('email', $input['data']['customer']['email'])->first();
//
//                $data['user_id']=$user->id;
//                $data['gateway']="Paystack";
//                $data['amount']=$input['data']['amount']/100;
//                $data['date']=Carbon::now();
//                $data['reference']=$input['data']['reference'];
//                $data['currency']=$input['data']['currency'];
//                $data['gateway_reference']=$input['data']['reference'];
//                $data['gateway_response']=$data2;
//
//                $p=PaymentModel::where('gateway_reference', $data['gateway_reference'])->first();
//
//                if(!$p) {
//                    $data['status'] = $input['status'];
//
//                    $data['plan']=$user->plan;
//
//                    $plan=PaymentModel::where();
//
//                    if($plan==1){
//                        $data['duration'] = "a month";
//                        User::where('id',Auth::id())->update(['subscription'=>Carbon::now()->addMonth(), 'status'=>'active']);
//                    }else{
//                        $data['duration'] = "a year";
//                        User::where('id',Auth::id())->update(['subscription'=>Carbon::now()->addYear(), 'status'=>'active']);
//                    }
//
//                    PaymentModel::create($data);
//
//                    return "subscribed";
//                }else{
//                    $data['status'] = 'Suspicious';
//
//                    if(session('job')=="change_plan") {
//                        $data['plan'] = session('plan');
//                    }else{
//                        $data['plan']=Auth::user()->plan;
//                    }
//
//                    PaymentModel::create($data);
//
//                    return "Suspicious";
//                }
//            }else{
//                return "Invalid Payment!";
//            }
//        }

        return "success";
    }
}
