<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function verify(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/123456/verify",
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

//        $response = curl_exec($curl);
        $response = '{ "status": "success", "message": "Transaction fetched successfully", "data": { "id": 1163068, "tx_ref": "akhlm-pstmn-blkchrge-xx6", "flw_ref": "FLW-M03K-02c21a8095c7e064b8b9714db834080b", "device_fingerprint": "N/A", "amount": 3000, "currency": "NGN", "charged_amount": 3000, "app_fee": 1000, "merchant_fee": 0, "processor_response": "Approved", "auth_model": "noauth", "ip": "pstmn", "narration": "Kendrick Graham", "status": "successful", "payment_type": "card", "created_at": "2020-03-11T19:22:07.000Z", "account_id": 73362, "amount_settled": 2000, "card": { "first_6digits": "553188", "last_4digits": "2950", "issuer": " CREDIT", "country": "NIGERIA NG", "type": "MASTERCARD", "token": "flw-t1nf-f9b3bf384cd30d6fca42b6df9d27bd2f-m03k", "expiry": "09/22" }, "customer": { "id": 252759, "name": "Kendrick Graham", "phone_number": "0813XXXXXXX", "email": "user@example.com", "created_at": "2020-01-15T13:26:24.000Z" } } }';

        curl_close($curl);
//        echo $response;

        $resp=json_decode($response, true);

        if($resp['status']=="success"){

            $data['user_id']=Auth::id();
            $data['plan']=Auth::user()->plan;
            $data['gateway']="Flutterwave";
            $data['amount']=$resp['data']['amount'];
            $data['date']=$resp['data']['created_at'];
            $data['reference']=$resp['data']['tx_ref'];
            $data['gateway_reference']=$resp['data']['flw_ref'];
            $data['gateway_response']=$response;

            $p=PaymentModel::where('gateway_reference', $data['gateway_reference'])->first();

            if(!$p) {
                $data['status'] = $resp['status'];
                User::where('id',Auth::id())->update(['subscription'=>Carbon::now()]);
                return redirect('room')->with('success', 'Your payment is successfully!');
            }else{
                $data['status'] = 'Suspicious';

                return back()
                    ->with('error', 'Kindly contact our support with reference -> '. $data['reference']);
            }
        }else{
            return back()
                ->with('error', 'Invalid Payment!');
        }

    }

    public function list(){
        $datas['payments']=PaymentModel::where('user_id', Auth::id())->get();
        $datas['sp']=PaymentModel::where('user_id', Auth::id())->sum('amount');
        $datas['tp']=PaymentModel::where('user_id', Auth::id())->count();
        $datas['pp']=PaymentModel::distinct('plan')->count();

        return view('user.payments', $datas);

    }
}
