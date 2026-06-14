<?php

namespace App\Jobs;

use App\Mail\InviteMail;
use App\Models\InvitesModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailInviteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public InvitesModel $inviteData;
    public string $type;

    public function __construct(InvitesModel $inviteData, string $type = 'create')
    {
        $this->inviteData = $inviteData;
        $this->type = $type;
    }

    public function handle()
    {
        Log::info("Running EmailInviteJob for " . $this->inviteData->guest);

        // Create ICS file
        $icsFile = $this->generateMeetingICS($this->inviteData, $this->type);

        // Get guest emails
        $guestEmails = explode(",", $this->inviteData->guest);

        //Get user email
        $user=User::find($this->inviteData->user_id);
        
        try {
            Mail::to($user->email)->cc($guestEmails)->send(new InviteMail($this->inviteData, $icsFile, $this->type));
            Log::info("Email sent successfully to " . $this->inviteData->guest);
        } catch (\Exception $e) {
            Log::error("Error when sending Konn3ct invite email: " . $e->getMessage());
        }

        // Clean up the ICS file
        if (file_exists($icsFile)) {
            unlink($icsFile);
        }
    }

    private function generateMeetingICS(InvitesModel $input, string $type = 'create'): string
    {
        $ranName = time() . rand() . '-' . $input->id;
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
}
