<?php

namespace App\Mail;

use App\Models\InvitesModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public InvitesModel $invite;
    public string $icsFile;
    public string $type;

    public function __construct(InvitesModel $invite, string $icsFile, string $type = 'create')
    {
        $this->invite = $invite;
        $this->icsFile = $icsFile;
        $this->type = $type;
    }

    private function getFormattedData(): array
    {
        $host = $this->invite->hostname;
        $link = $this->invite->roomlink;
        $timezone = $this->invite->timezone;
        $accessCode = $this->invite->accesscode ?? "No Access Code";

        // Parse date
        $date = Carbon::parse($this->invite->date, $timezone)->format('l, F j, Y');

        // Parse times with fallback end time
        $startTime = Carbon::parse($this->invite->time, $timezone)->format('g:i A');
        $endTimeRaw = !empty($this->invite->totime) ? $this->invite->totime : Carbon::parse($this->invite->time, $timezone)->addMinutes(30)->format('H:i');
        $endTime = Carbon::parse($endTimeRaw, $timezone)->format('g:i A');

        // Format recurrence text
        $recurrence = '';
        if (!empty($this->invite->recurrence) && $this->invite->recurrence !== 'once') {
            $recurrence = ucfirst($this->invite->recurrence);
        }

        return [
            'title' => $this->invite->title,
            'host' => $host,
            'link' => $link,
            'date' => $date,
            'time' => $startTime,
            'endTime' => $endTime,
            'timezone' => $timezone,
            'roomName' => $this->invite->roomname,
            'accessCode' => $accessCode,
            'additional' => $this->invite->additional ?? '',
            'recurrence' => $recurrence
        ];
    }

    public function build()
    {
        $formattedData = $this->getFormattedData();
        $subjectPrefix = $this->type === 'cancel' ? 'Meeting Cancelled' : ($this->type === 'update' ? 'Meeting Updated' : 'Konn3ct Invite');
        $template = $this->type === 'cancel' ? 'vendor.notifications.invite-cancel' : ($this->type === 'update' ? 'vendor.notifications.invite-update' : 'vendor.notifications.invite');

        $mail = $this->markdown($template)
            ->with($formattedData)
            ->subject($subjectPrefix . ': ' . $this->invite->title);

        // Only attach ICS for create and update
        if ($this->type !== 'cancel') {
            $mail->attach($this->icsFile, [
                'as' => 'Konn3ct_Event.ics',
                'mime' => 'text/calendar'
            ]);
        }

        return $mail;
    }
}
