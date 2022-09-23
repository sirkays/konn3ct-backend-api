<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushNotificationCallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $user_name, $caller;

    public function __construct($user_name, $caller)
    {
        $this->user_name = $user_name;
//        $this->message = $message;
//        $this->title = $title;
        $this->caller = $caller;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_name = $this->user_name;
//        $message = $this->message;
//        $title = $this->title;
        $caller = $this->caller;

        echo "sending call push notification to $user_name";

        $user_name_tr = str_replace(" ", "", $user_name);

        $name = $caller->firstname . $caller->lastname;

        $payload = '{
    "to": "/topics/1",
    "data": {
        "extra_information": "call",
        "call_type":"voice_call",
        "caller_name":"' . $name . '",
        "caller_email":"' . $caller->email . '",
        "caller_pic":"' . $caller->profile_photo_url . '"

    },
    "notification": {
        "title": "Call from ' . $name . '",
        "body": "Click here to continue call"
    }
}';

        Log::info("Push notification call");
        Log::info($payload);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                "Authorization: key=" . env('PUSH_NOTIFICATION_KEY'),
                "Content-Type: application/json",
                "Content-Type: text/plain"
            ),
        ));
        $uresponse = curl_exec($curl);

        echo $uresponse;

        $json = json_decode($uresponse, true);

//        DB::table('tbl_pushnotiflog')->insert(
//            ['user_name' => $user_name, 'message' => $message, 'response' => $json['message_id']]
//        );

    }
}
