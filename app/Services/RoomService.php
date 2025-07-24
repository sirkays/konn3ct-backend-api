<?php

namespace App\Services;


use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;

class RoomService {

    /**
     * Does something interesting
     *
     * @param User   $user  The user to create room for
     * @param string $name The name of the room
     * @param string $url The url of the room, if it is empty I will generate one here
     * @param string $access_code The code to restrict the room and it can be empty
     * @param string $default_room This can be yes or no. Yes means the room cannot be deleted.
     * @param string $internalMeetingID This can be user ID
     * @param string $parentMeetingID This can be team ID
     * @param string $muj Enable or disable Mute user on join
     * @param string $aujam Enable or disable All user join as moderator
     * @param string $dpuc Enable or disable Group Chat
     * @param string $ewma Enable or disable Webcam for Moderator alone
     * @param string $dum Enable or disable User Microphone
     * @param string $dsn Enable or disable Konn3ct Doc
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return RoomModel
     */

    public static function create(User $user, $name, $url, $access_code, $default_room,$internalMeetingID="",$parentMeetingID="",$muj="",$aujam="",$dpuc="",$ewma="",$dum="",$dsn="")
    {
        $plan = PlanModel::where("id", $user->plan)->first();
        $duration = $plan->duration;
        $max_user=$plan->participant;

        if ($url==""){
            $num=trim(date('siyh'));
            $shuffled = str_shuffle(substr($user->firstname, 0, 2) . substr(str_shuffle($num), 0, 4));
            $sfinal = substr($shuffled, 0, 6);

            if ($user->lastname == "") {
                $url = trim(substr($user->firstname, 0, 3) . $sfinal);
            } else {
                $url = trim(substr($user->lastname, 0, 3) . $sfinal);
            }

        }

        if ($access_code == "") {
            $input['password_attendee'] = "attendee";
            $input['password_moderator'] = "moderator";
        } else {
            $input['password_attendee'] = $access_code;
            $input['password_moderator'] = "moderator";
        }

        $input['welcome_message'] = "";
        $input['logout_url'] = url('/leftsession');
        $input['max_participants'] = $max_user;
        $input['duration'] = $duration;
        $input['url'] = preg_replace('/\s+/', '', str_replace(' ', '', $url));
        $input['access_code'] = $access_code;
        $input['user_id'] = $user->id;
        $input['default_room'] = $default_room;
        $input['name'] = $name;
        $input['muj'] = $muj;
        $input['aujam'] = $aujam;
        $input['dpuc'] = $dpuc;
        $input['ewma'] = $ewma;
        $input['dum'] = $dum;
        $input['dsn'] = $dsn;
        $input['internalMeetingID'] = $internalMeetingID;
        $input['parentMeetingID'] = $parentMeetingID;

        $r = RoomModel::create($input);

        return $r;
    }

    /**
     * Fetch room
     *
     * @param User   $user  The user to create room for
     * @param int $type 1 is private; 0 is public; 2 for both; 3 for rooms created by me
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return RoomModel
     */

    public static function fetch(User $user, int $type=1, string $search="")
    {
        if($type == 1){
            $r = RoomModel::where([["user_id", $user->id], ['name', 'LIKE', '%'.$search.'%']])->where('internalMeetingID',$user->id);
        }elseif($type == 2){
            $r = RoomModel::where([["user_id", $user->id], ['name', 'LIKE', '%'.$search.'%']])->orwhere([['parentMeetingID',$user->current_team_id], ['name', 'LIKE', '%'.$search.'%']]);
        }elseif($type == 3){
            $r = RoomModel::where([["user_id", $user->id], ['name', 'LIKE', '%'.$search.'%']]);
        }else{
            $r = RoomModel::where([['parentMeetingID',$user->current_team_id], ['name', 'LIKE', '%'.$search.'%']]);
        }

        return $r;
    }

    /**
     * Fetch room with the soft deleted ones
     *
     * @param User   $user  The user to create room for
     * @param int $private 1 is private; 0 is public; 2 for both; 3 for rooms created by me
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return RoomModel
     */

    public static function fetchWithTrashed(User $user, int $type=1)
    {
        if($type == 1){
            $r = RoomModel::withTrashed()->where("user_id", $user->id)->where('internalMeetingID',$user->id);
        }elseif($type == 2){
            $r = RoomModel::withTrashed()->where("user_id", $user->id)->orwhere('parentMeetingID',$user->current_team_id);
        }elseif($type == 3){
            $r = RoomModel::withTrashed()->where("user_id", $user->id);
        }else{
            $r = RoomModel::withTrashed()->where('parentMeetingID',$user->current_team_id);
        }

        return $r;
    }

    /**
     * Count rooms
     *
     * @param User   $user  The user to create room for
     * @param int $private 1 is private; 0 is public; 2 for both; 3 for rooms created by me
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return int
     */

    public static function count(User $user, int $type=1)
    {
        if($type == 1){
            $r = RoomModel::where("user_id", $user->id)->where('internalMeetingID',$user->id)->count();
        }elseif($type == 2) {
            $r = RoomModel::where("user_id", $user->id)->orwhere('parentMeetingID', $user->current_team_id)->count();
        }elseif($type == 3){
            $r = RoomModel::where("user_id", $user->id)->count();
        }else{
            $r = RoomModel::where('parentMeetingID',$user->current_team_id)->count();
        }

        return $r;
    }

}
