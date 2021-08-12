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

                    $pBody = '{ "body": "' . str_replace('/>', "", str_replace('<', "", str_replace('"', "'", $msg))) . '", "phone": ' . $GLOBALS['recipient'] . '  }';
                    echo $pBody;

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.chat-api.com/" . env("CHAT_API_INSTANCE") . "/sendMessage?token=" . env("CHAT_API_TOKEN"),
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $pBody
                    ));
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo $response;

                }

            } catch (Exception $e) {
                echo "error when sending whatsapp";
            }
        }

    }
}
