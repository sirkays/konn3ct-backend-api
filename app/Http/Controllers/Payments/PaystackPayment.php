<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\Controller;
use App\Models\PaymentModel;
use App\Models\PlanPricing;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Matscode\Paystack\Paystack;

class PaystackPayment extends Controller
{
    public function process($id)
    {
        $plan = PlanPricing::find($id);

        if (!$plan) {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->payment_gateway != "paystack") {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->plan_id == 1) {
            redirect()->route('dashboard')->with("error", "Invalid");
        }

        $ref = rand() . uniqid();

        if (env('APP_ENV') != "local") {
            $planCode = $plan->plan_code;
            $paystackSecret = env('PAYSTACK_SECRET_KEY');
        } else {
            $planCode = "PLN_hzw5ilaruq41vhh";
            $paystackSecret = env('PAYSTACK_SECRET_KEY_TEST');
        }

        $Paystack = new Paystack($paystackSecret);

        // Set data to post using this method
        $response = $Paystack->transaction
            ->setCallbackUrl(route('payment_verify_paystack', ['reference' => $ref]))
            ->setEmail(Auth::user()->email)
            ->setReference($ref)
            ->setPlan($planCode)
            ->initialize();

        return redirect()->away($response->data->authorization_url);
    }

    public function verify($reference)
    {
        if (env('APP_ENV') != "local") {
            $paystackSecret = env('PAYSTACK_SECRET_KEY');
        } else {
            $paystackSecret = env('PAYSTACK_SECRET_KEY_TEST');
        }

        $Paystack = new Paystack($paystackSecret);

        try {
            $resp = $Paystack->transaction->verify($reference);
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Invalid Payment!');
        }


        if ($resp->data->status != "success") {
            return redirect()->route('dashboard')->with('error', 'Payment not successful');
        }

        $data['user_id'] = Auth::id();
        $data['gateway'] = "Paystack";
        $data['amount'] = $resp->data->amount / 100;
        $data['date'] = Carbon::now();
        $data['reference'] = $resp->data->reference;
        $data['currency'] = $resp->data->currency;
        $data['gateway_reference'] = $resp->data->reference;
        $data['gateway_response'] = json_encode($resp);
        $data['status'] = "success";

        return $this->creditSubscription($data);

    }

    public function creditSubscription($data)
    {
        $plan = session('plan');

        $p = PaymentModel::where('gateway_reference', $data['gateway_reference'])->first();

        if ($p) {
            return back()->with('error', 'Invalid Payment Detected!');
        }

        if (session('job') == "change_plan") {
            $data['plan'] = session('plan');

            if ($plan == 2) {
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

            if ($plan == 2) {
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
    }

}
