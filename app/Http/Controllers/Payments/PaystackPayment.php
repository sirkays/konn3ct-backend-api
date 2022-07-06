<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Matscode\Paystack\Paystack;

class PaystackPayment extends Controller
{
    public function process()
    {

        $paystackSecret = env('PAYSTACK_SECRET_KEY');
        $Paystack = new Paystack($paystackSecret);

        $ref = rand() . uniqid();

        // Set data to post using this method
        $response = $Paystack->transaction
            ->setCallbackUrl(route('verifypaystackpayment', ['plan' => 1, 'id' => $ref]))
            ->setEmail('customer.email@gmail.com')
            ->setReference($ref)
            ->setPlan()
            ->initialize();

        return redirect()->away($response->data->authorization_url);
    }

    public function ipn()
    {

    }
}
