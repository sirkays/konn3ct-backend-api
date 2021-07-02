<?php

namespace App\Http\Controllers;

class MasterCardGatewayController extends Controller
{
    public function CreateSession(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://test-gateway.mastercard.com/api/nvp/version/60",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
    "apiOperation": "CREATE_CHECKOUT_SESSION",
    "apiPassword": "b9b4e2da78ac26f5fc5c74713b637e3e",
    "apiUsername": "merchant.GTB456789E01",
    "merchant": "GTB456789E01",
    "interaction.operation": "AUTHORIZE",
    "order": {
        "id": "11212215",
        "amount": "100",
        "currency": "USD"
    }
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: ci_session=49eb9643fe4d2be9ac2c172554b018f22ccfea5f'
            ),
        ));
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
