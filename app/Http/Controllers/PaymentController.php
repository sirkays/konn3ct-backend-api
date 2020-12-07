<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function verify($plan, $id){

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
            $response = '{ "status": "success", "message": "Transaction fetched successfully", "data": { "id": 1163068, "tx_ref": "akhlm-pstmn-blkchrge-xx6", "flw_ref": "FLW-M03ul55rK-052c5621a8095c7e064b8b9714db834080b", "device_fingerprint": "N/A", "amount": 3000, "currency": "NGN", "charged_amount": 3000, "app_fee": 1000, "merchant_fee": 0, "processor_response": "Approved", "auth_model": "noauth", "ip": "pstmn", "narration": "Kendrick Graham", "status": "successful", "payment_type": "card", "created_at": "2020-03-11T19:22:07.000Z", "account_id": 73362, "amount_settled": 2000, "card": { "first_6digits": "553188", "last_4digits": "2950", "issuer": " CREDIT", "country": "NIGERIA NG", "type": "MASTERCARD", "token": "flw-t1nf-f9b3bf384cd30d6fca42b6df9d27bd2f-m03k", "expiry": "09/22" }, "customer": { "id": 252759, "name": "Kendrick Graham", "phone_number": "0813XXXXXXX", "email": "user@example.com", "created_at": "2020-01-15T13:26:24.000Z" } } }';
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

            $p=PaymentModel::where('gateway_reference', $data['gateway_reference'])->first();

            if(!$p) {
                $data['status'] = $resp['status'];

                if(session('job')=="change_plan"){
                    $data['plan']=session('plan');

                    if($plan==1){
                        $data['duration'] = "a month";
                        User::where('id',Auth::id())->update(['subscription'=>Carbon::now()->addMonth(), 'plan'=>session('plan')]);
                    }else{
                        $data['duration'] = "a year";
                        User::where('id',Auth::id())->update(['subscription'=>Carbon::now()->addYear(), 'plan'=>session('plan')]);
                    }
                }else{
                    $data['plan']=Auth::user()->plan;

                    if($plan==1){
                        $data['duration'] = "a month";
                        User::where('id',Auth::id())->update(['subscription'=>Carbon::now()->addMonth()]);
                    }else{
                        $data['duration'] = "a year";
                        User::where('id',Auth::id())->update(['subscription'=>Carbon::now()->addYear()]);
                    }
                }

                PaymentModel::create($data);

                return redirect('room')->with('success', 'Your payment is successfully!');
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

    public function list(){
        $datas['payments']=PaymentModel::join('plans','plans.id','=','payment.plan')->where('payment.user_id', Auth::id())->select('payment.*', 'plans.name as plan')->OrderBy('id', 'desc')->limit(1)->get();
        $datas['sp']=PaymentModel::where('user_id', Auth::id())->sum('amount');
        $datas['tp']=PaymentModel::where('user_id', Auth::id())->count();
        $datas['pp']=PaymentModel::distinct('plan')->count();

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


    public function changeplan($plan){

        if($plan==1){
            User::where('id',Auth::id())->update(['subscription'=>Carbon::now(), 'plan'=>1]);

            return redirect('room')->with('success', 'Plan Changed Successfully!');
        }

        $datas['plan']=$plan;

        session(['plan' => $plan, 'job' =>'change_plan']);

        return view('payment', $datas);
    }
}
