<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WhatsappAppInviteAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $numbers;
    public $owner;

    public function __construct($numbers, $owner)
    {
        $this->numbers = $numbers;
        $this->owner = $owner;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // use of explode
        $str_arr = explode(",", $this->numbers);


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => env('QONTAK_BASEURL') . "/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => '{
    "username": "' . env('QONTAK_USERNAME') . '",
    "password": "' . env('QONTAK_PASSWORD') . '",
    "grant_type": "password",
    "client_id": "' . env('QONTAK_CLIENTID') . '",
    "client_secret": "' . env('QONTAK_CLIENTSECRET') . '"
}',
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

        $rep = json_decode($response, true);

        $token = $rep['access_token'];


        foreach ($str_arr as $arr) {

            $GLOBALS['recipient'] = trim($arr);

            if (str_contains($arr, "*") || str_contains($arr, "#")) {
                Log::alert("Number contain special character : $arr");
                $GLOBALS['recipient'] = "";
            }

            try {

                if ($GLOBALS['recipient'] != "") {

                    $invitee = $this->owner;

                    $payload = '{
    "to_number": "' . $GLOBALS['recipient'] . '",
    "to_name": "konn3ct_prospect",
    "message_template_id": "' . env('QONTAK_MESSAGE_TEMPLATEID_APP_INVITE_ALL') . '",
    "channel_integration_id": "' . env('QONTAK_CHANNEL_INTEGRATIONID') . '",
    "language": {
        "code": "en"
    },
    "parameters": {
        "body": [
            {
                "key": "1",
                "value": "invitee",
                "value_text": "' . $invitee . '"
            }
        ],
    }
}';

                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => env('QONTAK_BASEURL') . "/api/open/v1/broadcasts/whatsapp/direct",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_HTTPHEADER => [
                            "Authorization: Bearer $token",
                            "Content-Type: application/json"
                        ],
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }

                    Log::alert("WhatsappAppInviteAllJob sent to : $arr");

                }

            } catch (Exception $e) {
                echo "error when sending whatsapp";
                Log::alert("WhatsappAppInviteAllJob failed : $arr");
            }
        }

    }
}
