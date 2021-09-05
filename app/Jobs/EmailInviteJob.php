<?php

namespace App\Jobs;

use App\Mail\InviteMail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

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

                    $data['iroom'] = $this->input['roomname'];

                    $data['itimezone'] = $this->input['timezone'];

                    $data['iadditional'] = $this->input['additional'] ?? '';

                    Mail::to($GLOBALS['recipient'])->send(new InviteMail($data));
                }
            } catch (Exception $e) {
                echo "error when sending email";
            }
        }

    }
}
