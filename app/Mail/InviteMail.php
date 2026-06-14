<?php

namespace App\Mail;

use App\Models\InvitesModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public InvitesModel $invite;
    public string $icsFile;
    public array $formattedData;

    public function __construct(InvitesModel $invite, string $icsFile)
    {
        $this->invite = $invite;
        $this->icsFile = $icsFile;

        // Format dates and times nicely
        $timezone = $invite->timezone ?? 'UTC';
        $date = Carbon::parse($invite->date . ' ' . $invite->time, $timezone);
        $endDate = !empty($invite->totime) 
            ? Carbon::parse($invite->date . ' ' . $invite->totime, $timezone) 
            : $date->copy()->addMinutes(30);

        // Format recurrence text
        $recurrenceText = '';
        if (isset($invite->recurrence) && $invite->recurrence !== 'once') {
            switch ($invite->recurrence) {
                case 'daily':
                    $recurrenceText = 'Repeats daily';
                    break;
                case 'weekly':
                    $recurrenceText = 'Repeats weekly on ' . $date->format('l');
                    break;
                case 'monthly':
                    $recurrenceText = 'Repeats monthly on day ' . $date->day;
                    break;
            }
        }

        $this->formattedData = [
            'host' => $invite->hostname,
            'link' => $invite->roomlink,
            'date' => $date->format('l, F j, Y'),
            'time' => $date->format('g:i A'),
            'endTime' => $endDate->format('g:i A'),
            'timezone' => $timezone,
            'roomName' => $invite->roomname,
            'title' => $invite->title,
            'accessCode' => $invite->accesscode,
            'additional' => $invite->additional,
            'recurrence' => $recurrenceText
        ];
    }

    public function build()
    {
        return $this->markdown('vendor.notifications.invite')
            ->with($this->formattedData)
            ->subject('Konn3ct Invite: ' . $this->invite->title)
            ->attach($this->icsFile, [
                'as' => 'Konn3ct_Event.ics',
                'mime' => 'text/calendar'
            ]);
    }
}
