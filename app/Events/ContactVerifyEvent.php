<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactVerifyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ContactVerifyEvent');
    }


    public function broadcastAs()
    {
        return 'contact-verify-event';
    }

    public function broadcastWith()
    {
        $phones = $this->data;
        $pha = explode(",", $phones);

        foreach ($pha as $phone) {
            $user = User::where("phone", trim($phone))->first();

            if ($user) {
                $datam["email"] = $user->email;
                $datam["phone"] = $phone;
                $datam["name"] = $user->lastname . " " . $user->firstname;
                $data[] = $datam;
            }

        }

        return ['message' => 'Validated Successfully', 'data' => !empty($data) ? $data : [], 'status' => true];
    }
}
