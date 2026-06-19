<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $activationLink;

    public function __construct(string $activationLink)
    {
        $this->activationLink = $activationLink;
    }

    public function build()
    {
        return $this->markdown('emails.team-invite')
            ->with(['activationLink' => $this->activationLink])
            ->subject('You\'ve been invited to join a team on Konn3ct!');
    }
}
