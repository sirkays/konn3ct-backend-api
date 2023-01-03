<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\CouponController;
use App\Models\AddonModel;
use App\Models\PaymentModel;
use App\Models\PlanModel;
use App\Models\PlanPricing;
use App\Models\SettingsModel;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use PDF;

class PaymentController extends Controller
{
    public function verify($plan, $id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/" . $id . "/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . env("RAVE_SEC_KEY")
            ),
        ));

        if (App::environment(['local', 'staging'])) {
            // The environment is either local OR staging...
            $response = '{ "status": "success", "message": "Transaction fetched successfully", "data": { "id": 1163068, "tx_ref": "akhlm-pstmn-blkchrge-xx6526", "flw_ref": "FLW-M03ul55rK-052c5621a8095c7e064b8b9714db834080bb21", "device_fingerprint": "N/A", "amount": 3000, "currency": "NGN", "charged_amount": 3000, "app_fee": 1000, "merchant_fee": 0, "processor_response": "Approved", "auth_model": "noauth", "ip": "pstmn", "narration": "Kendrick Graham", "status": "successful", "payment_type": "card", "created_at": "2020-03-11T19:22:07.000Z", "account_id": 73362, "amount_settled": 2000, "card": { "first_6digits": "553188", "last_4digits": "2950", "issuer": " CREDIT", "country": "NIGERIA NG", "type": "MASTERCARD", "token": "flw-t1nf-f9b3bf384cd30d6fca42b6df9d27bd2f-m03k", "expiry": "09/22" }, "customer": { "id": 252759, "name": "Kendrick Graham", "phone_number": "0813XXXXXXX", "email": "user@example.com", "created_at": "2020-01-15T13:26:24.000Z" } } }';
        }else{
            $response = curl_exec($curl);
            curl_close($curl);
        }

//        echo $response;

        $resp=json_decode($response, true);

        if($resp['status']=="success"){

            $data['user_id']=Auth::id();
            $data['gateway']="Flutterwave";
            $data['amount']=$resp['data']['amount'];
            $data['date']=Carbon::now();
            $data['reference']=$resp['data']['tx_ref'];
            $data['currency']=$resp['data']['currency'];
            $data['gateway_reference']=$resp['data']['flw_ref'];
            $data['gateway_response']=$response;

            if (App::environment(['local', 'staging'])) {
                // The environment is either local OR staging...
                $p =false;
            }else{
                $p=PaymentModel::where('gateway_reference', $data['gateway_reference'])->first();
            }

            if(!$p) {
                $data['status'] = $resp['status'];

                if(session('job')=="change_plan"){
                    $data['plan']=session('plan');

                    if($plan==1){
                        $data['duration'] = "a month";
                        if($data['plan']==Auth::user()->plan){
                            if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                                $subd=Carbon::now()->addMonth();
                            }else{
                                $subd = Carbon::parse(Auth::user()->subscription)->addMonth();
                            }
                        }else{
                            $subd=Carbon::now()->addMonth();
                        }
                        User::where('id',Auth::id())->update(['subscription'=>$subd, 'plan'=>session('plan'), 'status'=>'active']);
                    }else{
                        $data['duration'] = "a year";

                        if($data['plan']==Auth::user()->plan){
                            if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                                $subd=Carbon::now()->addYear();
                            }else{
                                $subd = Carbon::parse(Auth::user()->subscription)->addYear();
                            }
                        }else{
                            $subd=Carbon::now()->addYear();
                        }

                        User::where('id',Auth::id())->update(['subscription'=>$subd, 'plan'=>session('plan'), 'status'=>'active']);
                    }
                }else{
                    $data['plan']=Auth::user()->plan;

                    if($plan==1){
                        $data['duration'] = "a month";
                        User::where('id', Auth::id())->update(['subscription' => Carbon::now()->addMonth(), 'status' => 'active']);
                    } else {
                        $data['duration'] = "a year";
                        User::where('id', Auth::id())->update(['subscription' => Carbon::now()->addYear(), 'status' => 'active']);
                    }
                }

                PaymentModel::create($data);


                $c = new CouponController();
                $c->markCouponCode();


                return redirect()->route('paymentreceipt')->with('success', 'Your payment is successfully!');
            }else{
                $data['status'] = 'Suspicious';

                if(session('job')=="change_plan") {
                    $data['plan'] = session('plan');
                }else{
                    $data['plan']=Auth::user()->plan;
                }

                PaymentModel::create($data);
                return back()
                    ->with('error', 'Kindly contact our support with reference -> '. $data['reference']);
            }
        }else{
            return back()
                ->with('error', 'Invalid Payment!');
        }

    }

    public function verifyPaystack($plan, $id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . env("PAYSTACK_PRV_KEY")
            ),
        ));

        if (App::environment(['local', 'staging'])) {
            // The environment is either local OR staging...
            $response = '{ "status": true, "message": "Verification successful", "data": { "amount": 27000, "currency": "NGN", "transaction_date": "2016-10-01T11:03:09.000Z", "status": "success", "reference": "DG4uishudoq90LD", "domain": "test", "metadata": 0, "gateway_response": "Successful", "message": null, "channel": "card", "ip_address": "41.1.25.1", "log": { "time_spent": 9, "attempts": 1, "authentication": null, "errors": 0, "success": true, "mobile": false, "input": [], "channel": null, "history": [ { "type": "input", "message": "Filled these fields: card number, card expiry, card cvv", "time": 7 }, { "type": "action", "message": "Attempted to pay", "time": 7 }, { "type": "success", "message": "Successfully paid", "time": 8 }, { "type": "close", "message": "Page closed", "time": 9 } ] }, "fees": null, "authorization": { "authorization_code": "AUTH_8dfhjjdt", "card_type": "visa", "last4": "1381", "exp_month": "08", "exp_year": "2018", "bin": "412345", "bank": "TEST BANK", "channel": "card", "signature": "SIG_idyuhgd87dUYSHO92D", "reusable": true, "country_code": "NG", "account_name": "BoJack Horseman" }, "customer": { "id": 84312, "customer_code": "CUS_hdhye17yj8qd2tx", "first_name": "BoJack", "last_name": "Horseman", "email": "bojack@horseman.com" }, "plan": "PLN_0as2m9n02cl0kp6", "requested_amount": 1500000 } }';
        }else{
            $response = curl_exec($curl);
            curl_close($curl);
        }

//        echo $response;

        $resp=json_decode($response, true);

        if($resp['data']['status']=="success"){

            $data['user_id']=Auth::id();
            $data['gateway']="Paystack";
            $data['amount']=$resp['data']['amount']/100;
            $data['date']=Carbon::now();
            $data['reference']=$resp['data']['reference'];
            $data['currency']=$resp['data']['currency'];
            $data['gateway_reference']=$resp['data']['reference'];
            $data['gateway_response']=$response;

            if (App::environment(['local', 'staging'])) {
                // The environment is either local OR staging...
                $p =false;
            }else{
                $p=PaymentModel::where('gateway_reference', $data['gateway_reference'])->first();
            }

            if(!$p) {
                $data['status'] = $resp['status'];

                if(session('job')=="change_plan"){
                    $data['plan']=session('plan');

                    if($plan==1){
                        $data['duration'] = "a month";
                        if($data['plan']==Auth::user()->plan){
                            if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                                $subd=Carbon::now()->addMonth();
                            }else{
                                $subd = Carbon::parse(Auth::user()->subscription)->addMonth();
                            }
                        }else{
                            $subd=Carbon::now()->addMonth();
                        }
                        User::where('id',Auth::id())->update(['subscription'=>$subd, 'plan'=>session('plan'), 'status'=>'active']);
                    }else{
                        $data['duration'] = "a year";

                        if($data['plan']==Auth::user()->plan){
                            if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                                $subd=Carbon::now()->addYear();
                            }else{
                                $subd = Carbon::parse(Auth::user()->subscription)->addYear();
                            }
                        }else{
                            $subd=Carbon::now()->addYear();
                        }

                        User::where('id',Auth::id())->update(['subscription'=>$subd, 'plan'=>session('plan'), 'status'=>'active']);
                    }
                }else{
                    $data['plan']=Auth::user()->plan;

                    if($plan==1){
                        $data['duration'] = "a month";
                        User::where('id', Auth::id())->update(['subscription' => Carbon::now()->addMonth(), 'status' => 'active']);
                    } else {
                        $data['duration'] = "a year";
                        User::where('id', Auth::id())->update(['subscription' => Carbon::now()->addYear(), 'status' => 'active']);
                    }
                }

                PaymentModel::create($data);


                $c = new CouponController();
                $c->markCouponCode();

                return redirect()->route('paymentreceipt')->with('success', 'Your payment is successfully!');
            }else{
                $data['status'] = 'Suspicious';

                if(session('job')=="change_plan") {
                    $data['plan'] = session('plan');
                }else{
                    $data['plan']=Auth::user()->plan;
                }

                PaymentModel::create($data);
                return back()
                    ->with('error', 'Kindly contact our support with reference -> '. $data['reference']);
            }
        }else{
            return back()
                ->with('error', 'Invalid Payment!');
        }

    }

    public function list(Request $request)
    {
        $datas['payments'] = PaymentModel::where('user_id', Auth::id())->with('planDetails')->OrderBy('id', 'desc')->get();
        $datas['sp'] = PaymentModel::where('user_id', Auth::id())->sum('amount');
        $datas['tp'] = PaymentModel::where('user_id', Auth::id())->count();
        $datas['pp'] = PaymentModel::distinct('plan')->count();

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


        return view('user.payments', $datas);

    }


    public function receipt(){
        $datas['payment']=PaymentModel::where('user_id', Auth::id())->orderBy('id', 'desc')->first();

        if(!$datas['payment']){
            $datas['payments']=PaymentModel::where('user_id', Auth::id())->get();
            $datas['sp']=PaymentModel::where('user_id', Auth::id())->sum('amount');
            $datas['tp']=PaymentModel::where('user_id', Auth::id())->count();
            return view('user.payments', $datas);
        }

        return view('user.receipt', $datas);

    }


    public function changeplan(Request $request, $plan)
    {

        if ($plan == 1) {
            User::where('id', Auth::id())->update(['subscription' => Carbon::now(), 'plan' => 1, 'status' => 'active']);

            return redirect()->route('rooms')->with('success', 'Plan Changed Successfully!');
        }

        $datas['plan'] = $plan;

        $input = $request->all();
        $vs = $input['vs'];

        if ($vs['countryCode'] == null) {
            $country = "NG";
        } else {
            $country = $vs['countryCode'];
        }

        if ($country == "IN") {
            $datas['pricing_monthly'] = PlanPricing::where([["plan_id", $plan], ["type", "monthly"], ["currency", "INR"]])->get();
            $datas['pricing_yearly'] = PlanPricing::where([["plan_id", $plan], ["type", "yearly"], ["currency", "INR"]])->get();
        } else {
            $datas['pricing_monthly'] = PlanPricing::where([["plan_id", $plan], ["type", "monthly"], ["currency", "NGN"]])->orWhere([["plan_id", $plan], ["type", "monthly"], ["currency", "USD"]])->get();
            $datas['pricing_yearly'] = PlanPricing::where([["plan_id", $plan], ["type", "yearly"], ["currency", "NGN"]])->orWhere([["plan_id", $plan], ["type", "yearly"], ["currency", "USD"]])->get();
        }

        session(['plan' => $plan, 'job' => 'change_plan']);

        return view('payment', $datas);
    }

    // Export to PDF
    public function exportreceipt() {

        $datas['payment']=PaymentModel::where('user_id', Auth::id())->orderBy('id', 'desc')->first();

        if($datas['payment']){
            $datas['payments']=PaymentModel::where('user_id', Auth::id())->get();
            $datas['sp']=PaymentModel::where('user_id', Auth::id())->sum('amount');
            $datas['tp']=PaymentModel::where('user_id', Auth::id())->count();

            view()->share('p', $datas);
            $pdf_doc = PDF::loadView('user.receiptpdf', $datas);

            return $pdf_doc->download('receipt.pdf');
        }else{
            return redirect()->route('rooms')->with('error', 'Error in exporting pdf!');
        }

    }

    public function activatefree(){
        $u=User::find(Auth::id());
        if($u->freetrial){
            return redirect()->route('rooms')->with('error', 'Free trial has been activated already');
        }else{
            $set=SettingsModel::first();
            $exp=Carbon::now()->addDays($set->freetrial_days);
            $u->subscription=$exp;
            $u->plan=3;
            $u->status="free_trial";
            $u->freetrial=true;
            $u->save();
            return redirect()->route('rooms')->with('success', 'Free trial has been activated successfully');
        }
    }

    public function verifyAddonsub($plan, $id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/".$id."/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".env("RAVE_SEC_KEY")
            ),
        ));

        if (App::environment(['local', 'staging'])) {
            // The environment is either local OR staging...
            $response = '{ "status": "success", "message": "Transaction fetched successfully", "data": { "id": 1163068, "tx_ref": "akhlm-pstmn-blkchrge-xx6526", "flw_ref": "FLW-M03ul55rK-052c5621a8095c7e064b8b9714db834080bb21", "device_fingerprint": "N/A", "amount": 3000, "currency": "NGN", "charged_amount": 3000, "app_fee": 1000, "merchant_fee": 0, "processor_response": "Approved", "auth_model": "noauth", "ip": "pstmn", "narration": "Kendrick Graham", "status": "successful", "payment_type": "card", "created_at": "2020-03-11T19:22:07.000Z", "account_id": 73362, "amount_settled": 2000, "card": { "first_6digits": "553188", "last_4digits": "2950", "issuer": " CREDIT", "country": "NIGERIA NG", "type": "MASTERCARD", "token": "flw-t1nf-f9b3bf384cd30d6fca42b6df9d27bd2f-m03k", "expiry": "09/22" }, "customer": { "id": 252759, "name": "Kendrick Graham", "phone_number": "0813XXXXXXX", "email": "user@example.com", "created_at": "2020-01-15T13:26:24.000Z" } } }';
        }else{
            $response = curl_exec($curl);
            curl_close($curl);
        }

//        echo $response;

        $resp=json_decode($response, true);

        if($resp['status']=="success"){

            $data['user_id']=Auth::id();
            $data['gateway']="Flutterwave";
            $data['amount']=$resp['data']['amount'];
            $data['date']=Carbon::now();
            $data['reference']=$resp['data']['tx_ref'];
            $data['currency']=$resp['data']['currency'];
            $data['gateway_reference']=$resp['data']['flw_ref'];
            $data['gateway_response']=$response;

            if (App::environment(['local', 'staging'])) {
                // The environment is either local OR staging...
                $p =false;
            }else{
                $p=PaymentModel::where('gateway_reference', $data['gateway_reference'])->first();
            }

            if(!$p) {
                $data['status'] = $resp['status'];
                $data['plan'] = $plan;

                PaymentModel::create($data);

                $addons = AddonModel::find($plan);

                if ($addons->name == "Whatsapp Invite") {
                    if (Auth::user()->whatsapp_invite == "0") {
                        $subd = Carbon::now()->addMonth();
                    } else if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->whatsapp_invite), false) < 0) {
                        $subd = Carbon::now()->addMonth();
                    } else {
                        $subd = Carbon::parse(Auth::user()->whatsapp_invite)->addMonth();
                    }
                    User::where('id', Auth::id())->update(['whatsapp_invite' => $subd]);
                }

                if ($addons->name == "Room Bundles - 10") {
                    $prv = Auth::user()->room_bundles;
                    User::where('id', Auth::id())->update(['room_bundles' => $prv + 10]);
                }


                if ($addons->name == "Streaming Service") {
                    if (Auth::user()->streaming_service == "0") {
                        $subd = Carbon::now()->addMonth();
                    } else if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->streaming_service), false) < 0) {
                        $subd = Carbon::now()->addMonth();
                    } else {
                        $subd = Carbon::parse(Auth::user()->streaming_service)->addMonth();
                    }
                    User::where('id', Auth::id())->update(['streaming_service' => $subd]);
                }

                return redirect('addonsubscription')->with('success', 'Your payment is successfully!');
            }else{
                $data['status'] = 'Suspicious';

                if (session('job') == "change_plan") {
                    $data['plan'] = session('plan');
                } else {
                    $data['plan'] = Auth::user()->plan;
                }

                PaymentModel::create($data);
                return back()
                    ->with('error', 'Kindly contact our support with reference -> ' . $data['reference']);
            }
        } else {
            return back()
                ->with('error', 'Invalid Payment!');
        }

    }

    // make payment
    public function makePayment()
    {

        $datas['plans'] = PlanModel::where('id', '!=', Auth::user()->plan)->get();
        return view('user.make_payment', $datas);
    }

    public function creditSub($p, $resp, $plan)
    {
        if (!$p) {
            $data['status'] = $resp['status'];

            if (session('job') == "change_plan") {
                $data['plan'] = session('plan');

                if ($plan == 1) {
                    $data['duration'] = "a month";
                    if ($data['plan'] == Auth::user()->plan) {
                        if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                            $subd = Carbon::now()->addMonth();
                        } else {
                            $subd = Carbon::parse(Auth::user()->subscription)->addMonth();
                        }
                    } else {
                        $subd = Carbon::now()->addMonth();
                    }
                    User::where('id', Auth::id())->update(['subscription' => $subd, 'plan' => session('plan'), 'status' => 'active']);
                } else {
                    $data['duration'] = "a year";

                    if ($data['plan'] == Auth::user()->plan) {
                        if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                            $subd = Carbon::now()->addYear();
                        } else {
                            $subd = Carbon::parse(Auth::user()->subscription)->addYear();
                        }
                    } else {
                        $subd = Carbon::now()->addYear();
                    }

                    User::where('id', Auth::id())->update(['subscription' => $subd, 'plan' => session('plan'), 'status' => 'active']);
                }
            } else {
                $data['plan'] = Auth::user()->plan;

                if ($plan == 1) {
                    $data['duration'] = "a month";
                    User::where('id', Auth::id())->update(['subscription' => Carbon::now()->addMonth(), 'status' => 'active']);
                } else {
                    $data['duration'] = "a year";
                    User::where('id', Auth::id())->update(['subscription' => Carbon::now()->addYear(), 'status' => 'active']);
                }
            }

            PaymentModel::create($data);


            $c = new CouponController();
            $c->markCouponCode();


            return redirect()->route('rooms')->with('success', 'Your payment is successfully!');
        } else {
            $data['status'] = 'Suspicious';

            if (session('job') == "change_plan") {
                $data['plan'] = session('plan');
            } else {
                $data['plan'] = Auth::user()->plan;
            }

            PaymentModel::create($data);
            return back()
                ->with('error', 'Kindly contact our support with reference -> ' . $data['reference']);
        }
    }

}
