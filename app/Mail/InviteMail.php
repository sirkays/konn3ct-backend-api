<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
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
        return $this->markdown('vendor.notifications.invite')
            ->with(['ihost'=>$this->data['ihost'], 'ilink'=>$this->data['ilink'], 'idate'=>$this->data['idate'], 'itime'=>$this->data['itime'], 'iroom'=>$this->data['iroom'], 'imtitle'=>$this->data['imtitle'], 'itimezone'=>$this->data['itimezone'], 'iaccesscode'=>$this->data['iaccesscode'], 'iadditional'=>$this->data['iadditional']??'' ])
            ->subject('Konn3ct Invite - '.$this->data['imtitle'])->attach($this->ifile, [
                'as' => 'Event.ics'
            ]);
    }
}
