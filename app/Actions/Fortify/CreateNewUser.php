<?php

namespace App\Actions\Fortify;

use App\Mail\UserWelcomeMail;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\SettingsModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'phone' => 'required|regex:/(0)[0-9]{1}[0-1]{1}[0-9]{8}/',
            'phone' => 'required',
            'password' => $this->passwordRules(),
        ])->validate();


        if(!isset($input['type'])){
            if($input['lastname']==""){
                Validator::make($input, [
                    'lastname' => ['required', 'string', 'max:255'],
                ])->validate();
            }
        }

        if(isset($input['referral'])){
                Validator::make($input, [
                    'referral' => ['required', 'string', 'max:6', 'exists:users,referral_code'],
                ])->validate();
//            }
        }

        $u=User::create([
            'firstname' => $input['firstname'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'plan' => session('plan'),
            'referral_code' => trim(substr(date('iym').rand(), 0, 6)),
            'password' => Hash::make($input['password']),
        ]);

        if(!isset($input['type'])){
            if($input['lastname']!=""){
                $u->lastname=$input['lastname'];
                $u->save();
            }
        }

        if(isset($input['referral'])){
            $u->referral=$input['referral'];
            $u->save();
        }

        if(isset($input['freetrial'])){
            $set=SettingsModel::first();
            $exp=Carbon::now()->addDays($set->freetrial_days);
            $u->subscription=$exp;
            $u->plan=3;
            $u->status="free_trial";
            $u->save();
        }
//
            $plan = PlanModel::where("id", $u->plan)->first();
            $duration = $plan->duration;
            $max_user = $plan->participant;

            $num = trim(substr($input['firstname'] ,0, 2).date('siyh'));
            $shuffled = str_shuffle($num);
            $sfinal = substr($shuffled, 0, 4);
            $input['name'] = $input['firstname'] ." Room";
            $input['password_attendee'] = "attendee";
            $input['password_moderator'] = "moderator";
            $input['url'] = trim(substr($input['firstname'], 0, 3) . $sfinal);
            $input['welcome_message']="";
            $input['logout_url']=url('/leftsession');
            $input['max_participants']=$max_user;
            $input['duration']=$duration;
            $input['user_id']=$u->id;
            $input['default_room']="yes";

            RoomModel::create($input);

            $data['messag']="";

        $GLOBALS['recipient']=$u->email;
        Mail::send(['html'=>'mail.welcome'], $data, function ($message) {
            $message->to($GLOBALS['recipient'])->subject('Welcome to konn3ct!');
        });

        Mail::to($u->email)->send(new UserWelcomeMail());

            return $u;
    }
}
