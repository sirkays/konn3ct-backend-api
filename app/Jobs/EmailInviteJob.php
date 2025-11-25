<?php

namespace App\Jobs;

use App\Mail\InviteMail;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    public $input;

    public function __construct($input)
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
        $str_arr = explode(",", $this->input['guest']);

        foreach ($str_arr as $arr) {

            $GLOBALS['recipient'] = trim($arr);

            try {
                if ($GLOBALS['recipient'] != "") {

                    $data['ihost'] = $this->input['hostname'];

                    $data['ilink'] = $this->input['roomlink'];

                    $data['iaccesscode'] = $this->input['accesscode'];

                    $data['imtitle'] = $this->input['title'];

                    $data['idate'] = $this->input['date'];

                    $data['itime'] = $this->input['time'];
                    $data['totime'] = Carbon::parse($this->input['date']. " ".$this->input['time'])->addMinutes(30)->format('H:i:s');

                    $data['iroom'] = $this->input['roomname'];

                    $data['itimezone'] = trim($this->input['timezone']);

                    $data['iadditional'] = $this->input['additional'] ?? '';


                    $s=$this->generateMeetingICS($data);

                    Mail::to($GLOBALS['recipient'])->send(new InviteMail($data, $s));
                }
            } catch (Exception $e) {
                echo "error when sending email ".$e->getMessage();
            }
        }

    }


    private function generateMeetingICS($data):string{
        $ranName=time().rand();
        $filename=$ranName.".ics";
        $dt=Carbon::parse($data['idate']." ".$data['itime'], $data['itimezone'])->setTimezone('UTC')->format('Ymd\THis\Z');
        $de=Carbon::parse($data['idate']." ".$data['totime'], $data['itimezone'])->setTimezone('UTC')->format('Ymd\THis\Z');

        $eContent='BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
SUMMARY:'.$data["imtitle"].'
DTSTART:'.$dt.'
DTEND:'.$de.'
DTSTAMP:'.$de.'
UID:'.$ranName.'-conference
DESCRIPTION:Pre-check Your Setup: Test your microphone, camera, and connection before joining to ensure a smooth experience.
LOCATION:'.$data["ilink"].'
ORGANIZER;CN='.$data['ihost'].':mailto:info@konn3ct.com
STATUS:TENTATIVE
PRIORITY:1
END:VEVENT
END:VCALENDAR
';
        Storage::put($filename,$eContent);

        return Storage::path($filename);
    }

    private function removeMeetingICS($path){
        Storage::delete($path);
    }

}
