<?php

namespace App\Jobs;

use App\Models\EnrolledChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class KCEnrollOwnerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $user_id;
    public $room_id;

    public function __construct($room_id, $user_id)
    {
        $this->user_id = $user_id;
        $this->room_id = $room_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        EnrolledChat::create([
            'user_id' => $this->user_id,
            'room_id' => $this->room_id,
            'owner' => 1
        ]);
    }
}
