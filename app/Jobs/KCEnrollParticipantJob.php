<?php

namespace App\Jobs;

use App\Models\EnrolledChat;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class KCEnrollParticipantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $user_email;
    public $room_id;

    public function __construct($room_id, $user_email)
    {
        $this->user_email = $user_email;
        $this->room_id = $room_id;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where("email", $this->user_email)->first();

        if ($user) {
            EnrolledChat::create([
                'user_id' => $user->id,
                'room_id' => $this->room_id,
            ]);
        }
    }
}
