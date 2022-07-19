<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\PlanPricing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StripePayment extends Controller
{
    public function process($id)
    {
        $plan = PlanPricing::find($id);

        if (!$plan) {
            return redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->payment_gateway != "stripe") {
            return redirect()->route('dashboard')->with("error", "Invalid");
        }

        if ($plan->plan_id == 1) {
            return redirect()->route('dashboard')->with("error", "Invalid");
        }

        $amount = CheckForDiscount($plan->price, $plan->type);

        if (env('APP_ENV') != "local") {
            $key = env('STRIPE_SECRET_KEY');
        } else {
            $key = env('STRIPE_SECRET_KEY_TEST');
        }

        \Stripe\Stripe::setApiKey($key);

        $session = \Stripe\Checkout\Session::create([
            'customer_email' => Auth::user()->email,
            'line_items' => [[
                'name' => "konn3ct",
                'description' => 'Pay with Stripe',
                'images' => ["https://konn3ct.com/assets/images/group99@2x.png"],
                'amount' => round($amount, 2) * 100,
                'currency' => $plan->currency,
                'quantity' => 1,
            ]],
            'cancel_url' => route('dashboard'),
            'success_url' => route('payment_verify_stripe'),
        ]);

        session(['stripeID' => $session['id']]);

//        echo $session['id'];
//        return $session;

        return redirect()->away($session['url']);
    }

    public function verify()
    {

        if (env('APP_ENV') != "local") {
            $key = env('STRIPE_SECRET_KEY');
        } else {
            $key = env('STRIPE_SECRET_KEY_TEST');
        }

        $reference = session('stripeID');

        $url = "https://api.stripe.com/v1/checkout/sessions/" . $reference;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Bearer " . $key,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $reply = json_decode($resp, true);

        if (!isset($reply['payment_status'])) {
            return redirect()->route('dashboard')->with("error", "Payment Status Not found");
        }

        $session_id = $reply['id'];
        $payment_intent = $reply['payment_intent'];
        $payment_status = $reply['payment_status'];

        if ($payment_status != 'paid') {
            return redirect()->route('dashboard')->with("error", "Payment Not Made Yet");
        }

        $data['user_id'] = Auth::id();
        $data['gateway'] = "Stripe";
        $data['amount'] = $reply['amount_total'] / 100;
        $data['date'] = Carbon::now();
        $data['reference'] = $session_id;
        $data['currency'] = strtoupper($reply['currency']);
        $data['gateway_reference'] = $reply['payment_intent'];
        $data['gateway_response'] = $resp;
        $data['status'] = "success";

        $cr = new PaystackPayment();
        return $cr->creditSubscription($data);

    }

    public function ipn(Request $request)
    {
        $StripeAcc = GatewayCurrency::where('gateway_alias', 'StripeV3')->orderBy('id', 'desc')->first();
        $gateway_parameter = json_decode($StripeAcc->gateway_parameter);

        $key = "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";

        \Stripe\Stripe::setApiKey($key);

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = $gateway_parameter->end_point; // main
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];


        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the checkout.session.completed event

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $deposit = Order::where('api_response', $session->id)->orderBy('id', 'DESC')->first();

            if ($deposit->status == 0) {
                $type = "Stripe Checkout";
                PaymentController::userOrderupdate($deposit->trx, $type);
                //PaymentController::userDataUpdate($deposit->trx);
            }
        }
        http_response_code(200);
    }
}
