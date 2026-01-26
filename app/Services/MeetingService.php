<?php

namespace App\Services;


use App\Models\MeetingsModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use Illuminate\Support\Facades\Log;

class MeetingService {

    /**
     * Start Meeting
     *
     * @param User   $user  The User
     * @param RoomModel $name The Room
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return bool
     */

    public static function startMeeting(User $user, RoomModel $room, String $name="", String $logouturl="", String $message="") :bool
    {
        $plan = PlanModel::where("id", $user->plan)->first();

        if ($plan->recording) {
            $record = true;
        } else {
            $record = false;
        }

        $duration = $plan->duration;
        $max_user = $plan->participant;

        if ($room->muj) {
            $muj = true;
        } else {
            $muj = false;
        }

        if ($room->dpuc) {
            $dpuc = true;
        } else {
            $dpuc = false;
        }

        if ($room->dprc) {
            $dprc = true;
        } else {
            $dprc = false;
        }

        if ($room->ewma) {
            $ewma = true;
        } else {
            $ewma = false;
        }

        if ($room->dum) {
            $dum = true;
        } else {
            $dum = false;
        }

        if ($room->dsn) {
            $dsn = true;
        } else {
            $dsn = false;
        }

        if ($room->aujam) {
            $up = "moderator";
        } else {
            $up = $room->password_attendee;
        }

        if ($room->banner != "") {
            $banner = url('/') . "/myroombanner/" . $room->banner;
        } else {
            $banner = "https://konn3ct.com/assets/images/konn3ct_logo.png";
        }

        $mdata['meeting_id'] = "$room->id";
        $mdata['name'] = $user->lastname . " " . $user->firstname;
        $mdata['email'] = $user->email;
        $mdata['password_attendee'] = $up;
        $mdata['status'] = "start meeting";
        $mdata['identifier'] = $room->id . rand();
        MeetingsModel::create($mdata);

        $rm_id="0".$room->id;

        if($name == ""){
            $name=$room->name;
        }

        if($logouturl == ""){
            $logouturl=url('/leftsession');
        }

        if($message == ""){
            $message='Welcome to konn3ct!<br><br>Host: ' . $user->firstname . ' <br/> Meeting Link: <a href="' . url("/join/") . '/' . $room->url . '"> ' . url("/join/") . '/' . $room->url . '</a>  <br/>Dial-In: <span style="color: #008b8b;">%%DIALNUM%%</span> <br/>SIP: ' . env('SIP_URI') . ' <br/>PIN: %%CONFNUM%%';
        }

        $bbb = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters($rm_id,$name);
        $createMeetingParams->setModeratorPW($room->password_moderator);
        $createMeetingParams->setAttendeePW($up);
        $createMeetingParams->setMeetingEndedURL($logouturl);
        $createMeetingParams->setLogoutURL($logouturl);
        $createMeetingParams->setWelcome($message);
        $createMeetingParams->setAllowStartStopRecording($record);
        $createMeetingParams->setRecord($record);
        $createMeetingParams->setDuration($duration);
        $createMeetingParams->setMaxParticipants($max_user);
        $createMeetingParams->setMuteOnStart($muj);
        $createMeetingParams->setLockSettingsDisablePublicChat($dpuc);
        $createMeetingParams->setLockSettingsDisablePrivateChat($dprc);
        $createMeetingParams->setLockSettingsDisableCam($ewma);
        $createMeetingParams->setLockSettingsDisableMic($dum);
        $createMeetingParams->setLockSettingsDisableNote($dsn);
        $createMeetingParams->setLearningDashboardEnabled(false);
//        $createMeetingParams->setGuestPolicyAskModerator();
        $createMeetingParams->setLogo($banner);

        $createMeetingResponse = $bbb->createMeeting($createMeetingParams);

        Log::info("$room->name | $room->id | Start Meeting | ".$createMeetingResponse->success() . "|".$createMeetingResponse->getMessage());

        if($createMeetingResponse->success()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Join Meeting
     *
     * @param RoomModel   $room  The Room
     * @param String   $email  Joinee Email
     * @param String   $userName  Joinee name
     * @param String   $password  Meeting Password/Role
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return String
     */

    public static function joinMeeting(RoomModel $room, String $email, String $userName, String $password):String
    {

        $u = User::where('email', $email)->first();
        $dp = "https://ui-avatars.com/api/?name=".urlencode($userName)."&background=random&color=fff&size=200";

        if($u) {
            if ($u->profile_photo_url != "" && $u->profile_photo_url != NULL) {

                $resul = $u->profile_photo_url;
                $findme = 'ui-avatars.com';
                $pos = strpos($resul, $findme);
                // Note our use of ===.  Simply == would not work as expected
                if ($pos === false) {
                    $dp = $u->profile_photo_url;
                }
            }
        }

        $rm_id="0".$room->id;

        $url = \Bigbluebutton::join([
            'meetingID' => $rm_id,
            'userName' => $userName,
            'userId' => $email,
            'password' => $password, //which user role want to join set password here
            'avatarUrl' => $dp,
            'customParameters' => [
                'userdata-bbb_auto_join_audio' => 'true',
                'userdata-bbb_enable_video' => 'true',
                'userdata-bbb_listen_only_mode' => 'false',
                'userdata-bbb_force_listen_only' => 'false',
                'userdata-bbb_skip_check_audio' => 'true',
                'meetingLink' => url('/join/').'/'.$room->url,
            ],
        ]);

        Log::info("$room->name | $room->id | $email | Room Join | ".$url);

        return $url;
    }

    /**
     * Fetch meeting status
     *
     * @param RoomModel  $room  The Room
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return bool
     */

    public static function meetingStatus(RoomModel $room):bool
    {
        $rm_id="0".$room->id;

        $bbb = new BigBlueButton();
        $isMeetingRunningParams = new IsMeetingRunningParameters($rm_id);
        $response = $bbb->isMeetingRunning($isMeetingRunningParams);


        Log::info($room->id." | isMeetingRunning |".$response->success() . "|".$response->isRunning());

        if ($response->success() && $response->isRunning()) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Fetch room recordings
     *
     * @param RoomModel  $room  The Room
     *
     * @return array
     *@throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     */

    public static function roomRecordings(RoomModel $room): array
    {
        $rm_id="0".$room->id.",".$room->id;
        $bbb = new BigBlueButton();
        $recordingParams = new GetRecordingsParameters();
        $recordingParams->setMeetingID($rm_id);
        $recordingParams->setState('any');

        $response = $bbb->getRecordings($recordingParams);

        if (!$response->success()) {
            return [];
        }

        $records = self::convertXmlRecordings($response->getRawXml()->recordings);

        Log::info($room->id." | roomRecordings |".$response->success() . "|".json_encode($records));

        return $records;
    }

    /**
     * Fetch rooms recordings
     *
     * @param RoomModel[]  $room  The Rooms
     *
     * @throws \Exception If something interesting cannot happen
     * @author Samji Diamond <sam_is_blessed>
     * @return array
     */

    public static function roomsRecordings($rooms):array
    {
        $bbb = new BigBlueButton();
        $recordingParams = new GetRecordingsParameters();

        $fer = [];

        foreach ($rooms as $r) {
            array_push($fer, $r->id);
            array_push($fer, "0".$r->id);
        }

        $recordingParams->setMeetingID(implode(',',$fer));
        $recordingParams->setState('any');
        $response = $bbb->getRecordings($recordingParams);

        if (!$response->success()) {
            return [];
        }

        $records = self::convertXmlRecordings($response->getRawXml()->recordings);

        Log::info("Many | roomsRecordings |".$response->success() . "|".json_encode($records));

        return $records;
    }



    /**
     * @param $xmlrecordings
     * required fields
     * meetingID
     *
     * optional fields
     * recordID
     * state
     * @return array
     */
    private static function convertXmlRecordings(\SimpleXMLElement $xmlrecordings): array
    {
        if (! is_null($xmlrecordings->recording) && count($xmlrecordings->recording) > 0) {
            $recordings = [];
            foreach ($xmlrecordings->recording as $r) {
                $av=XmlToArray($r);
                //Want to remove some values
                unset($av['internalMeetingID']);
                unset($av['metadata']);
                unset($av['breakout']);
                unset($av['data']);
                unset($av['rawSize']);
                $recordings[] = $av;
            }

            return $recordings;
        }

        return [];
    }


}
