<?php

namespace App\Actions\Fortify;

use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\SettingsModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'phone' => 'required|regex:/(0)[0-9]{1}[0-1]{1}[0-9]{8}/',
            'phone' => 'required',
            'password' => $this->passwordRules(),
        ])->validate();

        $u=User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'plan' => session('plan'),
            'password' => Hash::make($input['password']),
        ]);

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

            $num = trim(date('siyh'));
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
            return $u;
    }
}
