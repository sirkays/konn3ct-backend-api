<?php

namespace App\Jobs;

use App\Mail\InviteMail;
use App\Models\InvitesModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public InvitesModel $input;

    public function __construct(InvitesModel $input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // use of explode
        $guestEmails = explode(",", trim($this->input->guest));
        $icsFile = $this->generateMeetingICS($this->input);
        $host = User::find($this->input->user_id);

        try {
            Mail::to($host->email)->cc($guestEmails)->send(new InviteMail($this->input, $icsFile));
        } catch (Exception $e) {
            Log::error("Error when sending Konn3ct invite email: " . $e->getMessage());
        } finally {
            $this->removeMeetingICS($icsFile);
        }
    }


    private function generateMeetingICS(InvitesModel $input):string{
        $ranName = "konn3ct_event_" . $input->id . "_" . time() . "_" . rand();
        $filename = $ranName . ".ics";
        
        // Parse dates and times with correct property names (date, time, totime, timezone)
        $dt = Carbon::parse($input->date . " " . $input->time, $input->timezone)->setTimezone('UTC')->format('Ymd\THis\Z');
        
        // If totime exists, use it; otherwise add 30 minutes as default duration
        if (isset($input->totime) && !empty($input->totime)) {
            $de = Carbon::parse($input->date . " " . $input->totime, $input->timezone)->setTimezone('UTC')->format('Ymd\THis\Z');
        } else {
            $de = Carbon::parse($input->date . " " . $input->time, $input->timezone)->addMinutes(30)->setTimezone('UTC')->format('Ymd\THis\Z');
        }
        
        // Build recurrence rule if recurrence is not 'once'
        $rrule = '';
        if (isset($input->recurrence) && $input->recurrence != 'once') {
            switch ($input->recurrence) {
                case 'daily':
                    $rrule = 'RRULE:FREQ=DAILY';
                    break;
                case 'weekly':
                    $rrule = 'RRULE:FREQ=WEEKLY;BYDAY=' . strtoupper(substr(Carbon::parse($input->date, $input->timezone)->format('D'), 0, 2));
                    break;
                case 'monthly':
                    $rrule = 'RRULE:FREQ=MONTHLY;BYMONTHDAY=' . Carbon::parse($input->date, $input->timezone)->day;
                    break;
            }
        }
        
        $eContent = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//konn3ct//Conference//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
DTSTART:' . $dt . '
DTEND:' . $de . '
DTSTAMP:' . $dt . '
UID:' . $ranName . '-konn3ct@konn3ct.com
CREATED:' . $dt . '
DESCRIPTION:Pre-check Your Setup: Test your microphone, camera, and connection before joining to ensure a smooth experience.\n\nJoin link: ' . $input->roomlink . '
SUMMARY:' . $input->title . '
ORGANIZER;CN=' . $input->hostname . ':mailto:info@konn3ct.com
LOCATION:' . $input->roomlink . '
URL:' . $input->roomlink . '
STATUS:CONFIRMED
TRANSP:OPAQUE' . ($rrule ? "\n" . $rrule : '') . '
END:VEVENT
END:VCALENDAR
';
        
        Storage::put($filename, $eContent);

        return Storage::path($filename);
    }

    private function removeMeetingICS($path){
        Storage::delete($path);
    }

}
