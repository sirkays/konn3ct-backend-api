<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\PlanPricing;
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
            $response = $Paystack->transaction->verify($reference);
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Invalid Payment!');
        }

        dd($response);
    }

    public function ipn()
    {

    }
}
