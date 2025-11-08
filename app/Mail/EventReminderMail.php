<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $ifile;
    public function __construct($data,$ifile)
    {
        $this->data=$data;
        $this->ifile=$ifile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('vendor.notifications.eventreminder')
            ->with(['data'=>$this->data])
            ->subject('Event Reminder - '.$this->data['event_name'])->attach($this->ifile, [
                'as' => 'Event.ics'
            ]);
    }
}
