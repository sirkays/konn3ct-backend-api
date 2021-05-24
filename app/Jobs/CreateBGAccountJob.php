<?php

namespace App\Jobs;

use App\Mail\WelcomeMailViaJoin;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\SettingsModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateBGAccountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $jobi;

    public function __construct($jobi)
    {
        $this->jobi = $jobi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->jobi['name'];
        $email = $this->jobi['email'];
        $password = Str::random(8);

        $fname = $name . explode(" ");

        $set = SettingsModel::first();
        $exp = Carbon::now()->addDays($set->freetrial_days);
        $plan = 2;

        $u = User::create([
            'firstname' => $fname[1] ?? '',
            'lastname' => $fname[0],
            'email' => $email,
            'plan' => $plan,
            'password' => Hash::make($password),
            'subscription' => $exp,
            'status' => 'free_trial',
        ]);


        $plan = PlanModel::where("id", $plan)->first();
        $duration = $plan->duration;
        $max_user = $plan->participant;

        $num = trim(date('siyh'));
        $shuffled = str_shuffle($num);
        $sfin = substr($shuffled, 0, 4);
        $sfina = substr(strtolower($fname[0]), 0, 2);
        $sfinal = str_shuffle($sfin . $sfina);
        $input['name'] = $fname[0] . " Room";
        $input['password_attendee'] = "attendee";
        $input['password_moderator'] = "moderator";
        $input['url'] = trim(substr($name, 0, 3) . $sfinal);
        $input['welcome_message'] = "";
        $input['logout_url'] = url('/leftsession');
        $input['max_participants'] = $max_user;
        $input['duration'] = $duration;
        $input['user_id'] = $u->id;
        $input['default_room'] = "yes";

        RoomModel::create($input);

        try {
            Mail::to($u->email)->send(new WelcomeMailViaJoin());
        } catch (Exception $e) {
            echo $e;
        }

    }
}
