<?php

namespace App\Jobs;

use App\Models\EnrolledChat;
use App\Models\RoomModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyParticipantMeetingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $room_id;

    public function __construct($room_id)
    {
        $this->room_id = $room_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ecs = EnrolledChat::where("room_id", $this->room_id)->get();

        $room = RoomModel::find($this->room_id);

        $message = "You can now join the room.";
        $title = "$room->name is opened";

        foreach ($ecs as $ec) {
            PushNotificationJob::dispatch($ec->user_id, $message, $title);
        }
    }
}
