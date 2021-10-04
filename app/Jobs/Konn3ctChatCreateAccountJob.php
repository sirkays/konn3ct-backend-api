<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Konn3ctChatCreateAccountJob implements ShouldQueue
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
        $data = $this->input;

        if (!isset($data['type'])) {
            $name = $data['firstname'] . " " . $data['lastname'];
        } else {
            $name = $data['firstname'];
        }

        $username = str_replace(" ", "", htmlspecialchars(substr($data['firstname'], 0, 7) . rand()));

        $curl = curl_init();

        echo '{
    "email": "' . $data['email'] . '",
    "name": "' . $name . '",
    "username": "' . $username . '",
    "pass": "' . $data['password'] . '"
}';

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('KONN3CT_CHAT_URL') . '/api/v1/users.register',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "email": "' . $data['email'] . '",
    "name": "' . $name . '",
    "username": "' . $username . '",
    "pass": "' . $data['password'] . '"
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;

    }
}
