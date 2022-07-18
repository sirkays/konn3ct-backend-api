<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\CouponController;
use App\Models\PlanPricing;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasterCardGatewayController extends Controller
{
    public function launchView($id)
    {
        $plan = PlanPricing::find($id);

        if (!$plan) {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->payment_gateway != "mastercard") {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->plan_id == 1) {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        $data['amount'] = CheckForDiscount($plan->price, $plan->type);
        $data['plan'] = $id;
        $data['type'] = $plan->type;
        $data['ref'] = rand();
        return view('mastercard', $data);
    }

    public function makePayment(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'cardnumber' => 'required|max:255',
            'expiryMonth' => 'required|max:2',
            'expiryYear' => 'required|max:2',
            'cvv' => 'required|max:3',
            'ref' => 'required',
            'type' => 'required',
            'plan' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $order = $input['ref'];
        $transactionid = $input['ref'];
        $plan = $input['plan'];
        $type = $input['type'];

        $plan = PlanPricing::find($plan);

        if (!$plan) {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->payment_gateway != "mastercard") {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->plan_id == 1) {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        $amount = CheckForDiscount($plan->price, $plan->type);

        $card = $input['cardnumber'];
        $eMonth = $input['expiryMonth'];
        $eYear = $input['expiryYear'];
        $sCode = $input['cvv'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MASTERCARD_URL') . "merchant/" . env('MASTERCARD_MERCHANTID') . "/order/" . $order . "/transaction/" . $transactionid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => '{"apiOperation":"PAY","sourceOfFunds":{"type":"CARD","provided":{"card":{"number":"' . $card . '","expiry":{"month":"' . $eMonth . '","year":"' . $eYear . '"},"securityCode":"' . $sCode . '"}}},"order":{"amount":"' . $amount . '","currency":"USD"}}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . env('MASTERCARD_AUTH'),
                'Content-Type: application/json',
                'Cookie: ci_session=49eb9643fe4d2be9ac2c172554b018f22ccfea5f'
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        curl_close($curl);

//        echo $response;

//        dd($response);

        $rep = json_decode($response, true);

        try {
            if ($rep['response']['acquirerCode'] == "00") {
//            return redirect()->route('mastercard.status');

                if ($type == 1) {
                    $data['duration'] = "a month";
                    if ($plan == Auth::user()->plan) {
                        if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                            $subd = Carbon::now()->addMonth();
                        } else {
                            $subd = Carbon::parse(Auth::user()->subscription)->addMonth();
                        }
                    } else {
                        $subd = Carbon::now()->addMonth();
                    }
                    User::where('id', Auth::id())->update(['subscription' => $subd, 'plan' => $plan, 'status' => 'active']);
                } else {
                    $data['duration'] = "a year";

                    if ($plan == Auth::user()->plan) {
                        if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                            $subd = Carbon::now()->addYear();
                        } else {
                            $subd = Carbon::parse(Auth::user()->subscription)->addYear();
                        }
                    } else {
                        $subd = Carbon::now()->addYear();
                    }

                    User::where('id', Auth::id())->update(['subscription' => $subd, 'plan' => $plan, 'status' => 'active']);
                }

                $c = new CouponController();
                $c->markCouponCode();

                $data['message'] = $rep['response']['acquirerMessage'];
                $data['amount'] = $amount;
                $data['ref'] = $order;
                $data['status'] = $rep['response']['acquirerCode'];
                return view('mastercardStatus', $data);
            } else {
                return back()->withInput()->with('error', $rep['response']['acquirerMessage']);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Payment Error. Try again');
        }

    }

    public function paymentStatus()
    {
        $data['message'] = "Transaction Approved";
        $data['amount'] = "Transaction Approved";
        $data['ref'] = "Transaction Approved";
        return view('mastercardStatus');
    }

    public function CreateSessionO()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://test-gateway.mastercard.com/api/rest/version/60/merchant/GTB456789E01/session",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{ "session": { "authenticationLimit": 25 } }',
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;


    }
}
