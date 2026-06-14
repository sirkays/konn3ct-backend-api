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

    private function generateMeetingICS(InvitesModel $input, string $type = 'create'): string
    {
        $ranName = time() . rand();
        $filename = $ranName . ".ics";

        // Parse start and end times with timezone
        $dt = Carbon::parse($input->date . " " . $input->time, $input->timezone)->setTimezone('UTC')->format('Ymd\THis\Z');

        // Handle totime (fallback to 30 minutes if not set)
        $endTime = !empty($input->totime) ? $input->totime : Carbon::parse($input->time, $input->timezone)->addMinutes(30)->format('H:i');
        $de = Carbon::parse($input->date . " " . $endTime, $input->timezone)->setTimezone('UTC')->format('Ymd\THis\Z');

        $status = $type === 'cancel' ? 'CANCELLED' : ($type === 'update' ? 'CONFIRMED' : 'CONFIRMED');
        $method = $type === 'cancel' ? 'CANCEL' : 'REQUEST';

        $eContent = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Konn3ct//EN
CALSCALE:GREGORIAN
METHOD:' . $method . '
BEGIN:VEVENT
DTSTART:' . $dt . '
DTEND:' . $de . '
DTSTAMP:' . $de . '
UID:' . $ranName . '-conference
DESCRIPTION:Pre-check Your Setup: Test your microphone, camera, and connection before joining to ensure a smooth experience.
SUMMARY:' . $input->title . '
LOCATION:' . $input->roomlink . '
ORGANIZER;CN=' . $input->hostname . ':mailto:info@konn3ct.com
STATUS:' . $status . '
PRIORITY:1';

        // Add recurrence rule if needed and not cancelled
        if ($type !== 'cancel' && !empty($input->recurrence) && $input->recurrence !== 'once') {
            $freq = strtoupper($input->recurrence);
            $eContent .= '
RRULE:FREQ=' . $freq . ';INTERVAL=1';
        }

        $eContent .= '
END:VEVENT
END:VCALENDAR
';

        Storage::put($filename, $eContent);

        return Storage::path($filename);
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
