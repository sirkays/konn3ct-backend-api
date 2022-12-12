<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushNotificationChatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $enrolledChat, $message, $title;

    public function __construct($message, $title, $enrolledChat)
    {
        $this->message = $message;
        $this->title = $title;
        $this->enrolledChat = $enrolledChat;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $enrolledChat = $this->enrolledChat;
        $message = $this->message;
        $title = $this->title;

        $user_name_tr = $enrolledChat->room_id;

        echo "sending call push notification to $user_name_tr";

        $payload = '{
    "to": "/topics/' . $user_name_tr . '",
    "data": {
        "priority":"high",
        "extra_information": "chat",
        "id":"' . $user_name_tr . '",
        "sender_id":"' . $enrolledChat->user_id . '"
    },
    "notification": {
        "title": "' . $title . '",
        "body": "' . $message . '"
    },
    "priority":"high"
}';

        Log::info("Push notification chat");
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

    }
}
