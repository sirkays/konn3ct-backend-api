<?php

namespace App\Actions\Fortify;

use App\Mail\UserWelcomeMail;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\SettingsModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
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
     * @param array $input
     * @return User
     */
    public function create(array $input)
    {
        if(!isset($input['type'])) {
            $messages = [
                'firstname.required' => 'First name is required.',
                'firstname.max' => 'First name characters too long.',
                'firstname.min' => 'The firstname must be at least 3 characters long.',
                'firstname.string' => 'First name requires alphabet only.',
            ];
        }else{
            $messages = [
                'firstname.required' => 'Business Name is required.',
                'firstname.max' => 'Business Name characters too long.',
                'firstname.min' => 'The Business Name must be at least 3 characters long.',
                'firstname.string' => 'Business Name requires alphabet only.',
            ];
        }

        Validator::make($input, [
            'firstname' => ['required', 'string', 'min:3', 'max:30'],
            'email' => ['required', 'string', 'email', 'min:5', 'max:50', 'unique:users'],
//            'phone' => 'required|regex:/(0)[0-9]{1}[0-1]{1}[0-9]{8}/',
            'phone' => ['required', 'string', 'max:15'],
            'password' => $this->passwordRules(),
        ], $messages)->validate();

        $input['firstname']=htmlspecialchars($input['firstname']);
        $input['email']=htmlspecialchars($input['email']);
        $input['phone']=htmlspecialchars($input['phone']);

        if(!isset($input['type'])){
            if($input['lastname']==""){
                Validator::make($input, [
                    'lastname' => ['required', 'string', 'max:20'],
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
            'referral_code' => trim(substr(date('iym') . rand(), 0, 8)),
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

        $plan = PlanModel::where("id", $u->plan)->first();
        $duration = $plan->duration;
        $max_user = $plan->participant;

        $num = trim(date('siyh'));
        $shuffled = str_shuffle($num);
        $sfin = substr($shuffled, 0, 4);
        $sfina = substr(strtolower($input['firstname']), 0, 2);
        $sfinal = str_shuffle($sfin . $sfina);
        $input['name'] = $input['firstname'] . " Room";
        $input['password_attendee'] = "attendee";
        $input['password_moderator'] = "moderator";
        if (!isset($input['type'])) {
            $input['url'] = trim(substr($input['lastname'], 0, 3) . $sfinal);
        } else {
            $input['url'] = trim(substr($input['firstname'], 0, 3) . $sfinal);
        }

        $input['url'] = str_replace(' ', '', $input['url']);
        $input['welcome_message'] = "";
        $input['logout_url'] = url('/leftsession');
        $input['max_participants'] = $max_user;
        $input['duration'] = $duration;
        $input['user_id'] = $u->id;
        $input['default_room'] = "yes";

        RoomModel::create($input);

//        Konn3ctChatCreateAccountJob::dispatch($input)->delay(now()->addSecond());


        try {
            Mail::to($u->email)->send(new UserWelcomeMail());
        } catch (Exception $e) {
//            echo $e;
        }

        return $u;
    }
}
