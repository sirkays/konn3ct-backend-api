<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
            CURLOPT_POSTFIELDS =>'{ "apiOperation": "CREATE_CHECKOUT_SESSION", "apiPassword" : "hello", "apiUsername": "merchant.GTB456789E01", "merchant":"GTB456789E01", "interaction.operation": "AUTHORIZE", "order.id":"hello", "order.amount":"500", "order.currency" : "USD"}',
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
            CURLOPT_URL => "https://test-gateway.mastercard.com/api/rest/version/60/merchant/GTB456789E011/session",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>'{ "session": { "authenticationLimit": 25 } }',
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;



    }
}
