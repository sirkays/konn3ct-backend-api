<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripePayment extends Controller
{
    public function process()
    {
        $key = env('STRIPE_SECRET_KEY');

        \Stripe\Stripe::setApiKey($key);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => "konn3ct",
                'description' => 'Pay with Stripe',
                'images' => [asset('assets/images/logoIcon/logo.png')],
                'amount' => round(10, 2) * 100,
                'currency' => "USD",
                'quantity' => 1,
            ]],
            'cancel_url' => route('dashboard'),
            'success_url' => route('rooms'),
        ]);

//        return $session;

        return redirect()->away($session['url']);
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
