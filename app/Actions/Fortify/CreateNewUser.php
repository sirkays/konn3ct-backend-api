<?php

namespace App\Actions\Fortify;

use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;
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

        if (!App::environment(['local', 'staging'])) {

            $plan = PlanModel::where("id", $u->plan)->first();
            $duration = $plan->duration;
            $max_user = $plan->participant;

            $num = trim(date('siyh'));
            $shuffled = str_shuffle($num);
            $sfinal = substr($shuffled, 0, 4);
            $input['url'] = trim(substr($input['firstname'], 0, 3) . $sfinal);

            $r = RoomModel::create($input);

            $createMeeting = \Bigbluebutton::initCreateMeeting([
                'meetingID' => $r->id,
                'meetingName' => $input['name'],
                'attendeePW' => 'attendee',
                'moderatorPW' => 'moderator',
            ]);

            $createMeeting->setDuration($duration); //overwrite default configuration
            $createMeeting->setLogoutUrl(url('/leftsession')); //overwrite default configuration
            if ($plan->dialin) {
                $createMeeting->setDialNumber($input['dial_number']); //overwrite default configuration
            }
            if ($plan->recording) {
                $createMeeting->setAllowStartStopRecording(true); //overwrite default configuration
            }
            $createMeeting->setMaxParticipants($max_user); //overwrite default configuration
            $createMeeting->setWelcomeMessage("Share this link with people you want in this meeting. <strong>" . url('/join/') . "/" . $input['url'] . "</strong>"); //overwrite default configuration

            $bbb = \Bigbluebutton::create($createMeeting);

            $bba = json_decode($bbb, true);
            $rm = RoomModel::find($r->id);

            if ($bba["returncode"] == "SUCCESS") {
                $rm->user_id = Auth::id();
                $rm->bbb_returncode = $bba["returncode"];
                $rm->internalMeetingID = $bba["internalMeetingID"];
                $rm->parentMeetingID = $bba["parentMeetingID"];
                $rm->voiceBridge = $bba["voiceBridge"];
                $rm->createDate = $bba["createDate"];
                $rm->createTime = $bba["createTime"];
                $rm->save();

            } else {
                $rm->delete();
            }
        }

        return $u;
    }
}
