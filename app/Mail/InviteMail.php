<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('vendor.notifications.invite')
            ->with(['ihost'=>$this->data['ihost'], 'ilink'=>$this->data['ilink'], 'idate'=>$this->data['idate'], 'itime'=>$this->data['itime'], 'iroom'=>$this->data['iroom'], 'imtitle'=>$this->data['imtitle'] ])
            ->subject('Konn3ct Invite');
    }
}
