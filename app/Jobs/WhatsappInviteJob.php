<?php

namespace App\Jobs;

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

//        foreach ($str_arr as $arr) {
//
//            $GLOBALS['recipient'] = trim($arr);
//
//            echo '{ "body": '.$this->input['text'].' }';
//            echo $GLOBALS['recipient'];
//
//            try {
//                if ($GLOBALS['recipient'] != "") {
//
//                    $curl = curl_init();
//
//                    curl_setopt_array($curl, array(
//                        CURLOPT_URL => "https://api.promptapi.com/whatsapp/message/".$GLOBALS['recipient']."/text",
//                        CURLOPT_HTTPHEADER => array(
//                            "Content-Type: text/plain",
//                            "apikey: ".env('WHATSAPP_API')
//                        ),
//                        CURLOPT_RETURNTRANSFER => true,
//                        CURLOPT_ENCODING => "",
//                        CURLOPT_MAXREDIRS => 10,
//                        CURLOPT_TIMEOUT => 0,
//                        CURLOPT_FOLLOWLOCATION => true,
//                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                        CURLOPT_CUSTOMREQUEST => "POST",
//                        CURLOPT_POSTFIELDS =>'{ "body": '.$this->input['text'].' }'
//                    ));
//                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//
//                    $response = curl_exec($curl);
//
//                    curl_close($curl);
//                    echo $response;
//                }
//            } catch (Exception $e) {
//                echo "error when sending whatsapp";
//            }
//        }

    }
}
