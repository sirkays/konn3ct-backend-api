<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WhatsappInviteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // use of explode
        $str_arr = explode(",", $this->input['guest']);


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

//            echo '{ "body": '.$this->input['text'].' }';
//            echo $GLOBALS['recipient'];

            try {

                if ($GLOBALS['recipient'] != "") {

                    $data['ihost'] = $this->input['hostname'];

                    $data['ilink'] = $this->input['roomlink'];

                    $data['iaccesscode'] = $this->input['accesscode'];

                    $data['imtitle'] = $this->input['title'];

                    $data['idate'] = $this->input['date'];

                    $data['itime'] = $this->input['time'];

                    $data['iroom'] = $this->input['roomname'];

                    $data['itimezone'] = $this->input['timezone'];

                    $msg = '*Hello*,\nYou have been invited by ' . $data["ihost"] . ' to attend ' . $data["imtitle"] . ' scheduled as follows:\n\nMeeting Room Name: ' . $data["iroom"] . '\nAccess Code: ' . $data["iaccesscode"] . '\nDate: ' . $data["idate"] . '\nTime: ' . $data["itime"] . ' ' . $data["itimezone"] . '\n\nClick this link ' . $data["ilink"] . ' to join or copy and paste in your preferred browser.\n\nThank you.\n...............\nVisit https://konn3ct.com\n...Amazing Virtual Experience';

//                    $pBody = '{ "body": "' . str_replace('/>', "", str_replace('<', "", str_replace('"', "'", $msg))) . '", "phone": ' . $GLOBALS['recipient'] . '  }';
//                    echo $pBody;

//                    $curl = curl_init();
//
//                    curl_setopt_array($curl, array(
//                        CURLOPT_URL => "https://api.chat-api.com/" . env("CHAT_API_INSTANCE") . "/sendMessage?token=" . env("CHAT_API_TOKEN"),
//                        CURLOPT_HTTPHEADER => array(
//                            "Content-Type: application/json",
//                        ),
//                        CURLOPT_RETURNTRANSFER => true,
//                        CURLOPT_ENCODING => "",
//                        CURLOPT_MAXREDIRS => 10,
//                        CURLOPT_TIMEOUT => 0,
//                        CURLOPT_FOLLOWLOCATION => true,
//                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                        CURLOPT_CUSTOMREQUEST => "POST",
//                        CURLOPT_POSTFIELDS => $pBody
//                    ));
//                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//
//                    $response = curl_exec($curl);
//
//                    curl_close($curl);
//                    echo $response;

                    $payload = '{
    "to_number": "' . $GLOBALS['recipient'] . '",
    "to_name": "konn3ct_participant",
    "message_template_id": "' . env('QONTAK_MESSAGE_TEMPLATEID') . '",
    "channel_integration_id": "' . env('QONTAK_CHANNEL_INTEGRATIONID') . '",
    "language": {
        "code": "en"
    },
    "parameters": {
        "body": [
            {
                "key": "1",
                "value": "host",
                "value_text": "' . $data['ihost'] . '"
            },
            {
                "key": "8",
                "value": "link",
                "value_text": "' . $data['ilink'] . '"
            },
            {
                "key": "4",
                "value": "accesscode",
                "value_text": "' . $data['iaccesscode'] . '"
            },
            {
                "key": "2",
                "value": "mtitle",
                "value_text": "' . $data['imtitle'] . '"
            },
            {
                "key": "5",
                "value": "date",
                "value_text": "' . $data['idate'] . '"
            },
            {
                "key": "6",
                "value": "time",
                "value_text": "' . $data['itime'] . '"
            },
            {
                "key": "3",
                "value": "room",
                "value_text": "' . $data['iroom'] . '"
            },
            {
                "key": "7",
                "value": "timezone",
                "value_text": "' . $data['itimezone'] . '"
            }
        ],
        "buttons": [
            {
                "index": "0",
                "type": "url",
                "value": "' . urlencode(str_replace(url("/") . "/", "", $data['ilink'])) . '"
            }
        ]
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

                }

            } catch (Exception $e) {
                echo "error when sending whatsapp";
            }
        }

    }
}
