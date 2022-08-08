<?php

namespace App\Jobs;

use App\Mail\EventReminderMail;
use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use App\Models\RoomModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEventReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $preg_list;

    public function __construct(PreRegModel $preg_list)
    {
        $this->preg_list = $preg_list;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $preg_list = $this->preg_list;
        $users = PreRegUserModel::where("prereg_id", $preg_list->id)->get();
        $host = User::find($preg_list->user_id);
        $room = RoomModel::find($preg_list->room_id);

        echo "Working on event - " . $preg_list->title;
        echo "\n";

        foreach ($users as $user) {
            echo "Working on user - " . $user->name;
            echo "\n";
            $dat['pname'] = explode(" ", $user->name)[0];
            $dat['event_name'] = $preg_list->title;
            $dat['host'] = $host->lastname . " " . $host->firstname;
            $dat['formatted_date'] = Carbon::parse($preg_list->date)->toFormattedDateString();
            $dat['formatted_time'] = Carbon::parse($preg_list->time)->toTimeString();
            $dat['date'] = $preg_list->date;
            $dat['time'] = $preg_list->time;
            $dat['timezone'] = $preg_list->timezone;
            $dat['url'] = url("/join/" . $preg_list->url);
            $dat['hphone'] = $host->phone;
            $dat['hemail'] = $host->email;


            $input['hostname'] = $dat['host'];

            $input['roomlink'] = $dat['url'];

            $input['accesscode'] = "<<hidden";

            $input['title'] = $dat['event_name'];

            $input['date'] = $dat['date'];

            $input['time'] = $dat['time'];

            $input['roomname'] = $room->name;

            $input['timezone'] = $dat['timezone'];

            $input['guest'] = $user->phone;

            WhatsappInviteJob::dispatch($input)->delay(now()->addSeconds(5));

            echo "Sending event reminder to " . $user->email;
            Mail::to($user->email)->send(new EventReminderMail($dat));
        }
    }
}
