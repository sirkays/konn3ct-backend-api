<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class MasterCardGatewayController extends Controller
{
    public function makePayment(Request $request)
    {
        $input = $request->all();
        $order = "102541";
        $transactionid = "78687725272783422";

        $card = $input['cardnumber'];
        $eMonth = $input['expiryMonth'];
        $eYear = $input['expiryYear'];
        $sCode = $input['cvv'];
        $amount = "";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://test-gateway.mastercard.com/api/rest/version/61/merchant/GTB456789E01/order/" . $order . "/transaction/" . $transactionid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => '{"apiOperation":"PAY","sourceOfFunds":{"type":"CARD","provided":{"card":{"number":"' . $card . '","expiry":{"month":"' . $eMonth . '","year":"' . $eYear . '"},"securityCode":"' . $sCode . '"}}},"order":{"amount":"' . $amount . '","currency":"USD"}}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic bWVyY2hhbnQuR1RCNDU2Nzg5RTAxOjY5NWM1NjY0NTFlYWE1YjYzNTI5OWU1NjQ0ZWFkMzg3',
                'Content-Type: application/json',
                'Cookie: ci_session=49eb9643fe4d2be9ac2c172554b018f22ccfea5f'
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;

    }

    public function CreateSessionO(){
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
