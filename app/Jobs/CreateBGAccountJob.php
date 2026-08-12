<?php

namespace App\Jobs;

use App\Mail\WelcomeMailViaJoin;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\SettingsModel;
use App\Models\User;
use App\Services\Odoo\OdooPayloadFactory;
use App\Services\Odoo\OdooSignalDispatcher;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        $phone = $this->jobi['phone'] ?? "";
        $password = Str::random(8);

        $fname = explode(" ", $name);

        $set = SettingsModel::first();
        $exp = Carbon::now()->addDays($set->freetrial_days);
        $plan = 1;

        $check = User::where("email", $email)->first();

        if (!$check) {
            $u = User::create([
                'firstname' => $fname[1] ?? '',
                'lastname' => $fname[0],
                'email' => $email,
                'phone' => $phone,
                'plan' => $plan,
                'password' => Hash::make($password),
                'subscription' => $exp,
                'referral_code' => trim(substr(date('iym') . rand(), 0, 6)),
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
            $input['url'] = trim(str_replace(' ', '', substr($name, 0, 3) . $sfinal));
            $input['welcome_message'] = "";
            $input['logout_url'] = url('/leftsession');
            $input['max_participants'] = $max_user;
            $input['duration'] = $duration;
            $input['user_id'] = $u->id;
            $input['default_room'] = "yes";

            RoomModel::create($input);

            $data['email'] = $u->email;
            $data['password'] = $password;

            $jobi['firstname'] = $name;
            $jobi['email'] = $email;
            $jobi['password'] = $password;


            try {
                Mail::to($u->email)->send(new WelcomeMailViaJoin($data));
            } catch (Exception $e) {
                echo $e;
            }

            // --- Odoo API-026: USER_REGISTERED (paid_event_registration) ---
            // No trustworthy IP is available in a background queue job.
            try {
                $factory    = app(OdooPayloadFactory::class);
                $dispatcher = app(OdooSignalDispatcher::class);
                $fullName   = trim(($u->firstname ?? '') . ' ' . ($u->lastname ?? ''));
                $payload    = $factory->userRegistered(
                    $u->id,
                    $fullName,
                    $u->email ?? '',
                    null,
                    $u->referral ?? null,
                    'paid_event_registration',
                    null  // No trustworthy IP in a background job
                );
                $dispatcher->dispatch(
                    'USER_REGISTERED',
                    'user_registered',
                    'USER_REGISTERED:' . $u->id,
                    $payload
                );
            } catch (Exception $e) {
                Log::error('Odoo USER_REGISTERED dispatch failed in CreateBGAccountJob', [
                    'user_id' => $u->id ?? null,
                    'error'   => substr($e->getMessage(), 0, 300),
                ]);
            }
        }
    }
}
