<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeetingsModel;
use App\Models\PlanModel;
use App\Models\RoomModel;
use App\Models\User;
use Bigbluebutton;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function startRoom($id)
    {

        $i = RoomModel::find($id);

        if (!$i) {
            return response()->json(['success' => false, 'message' => 'Invalid Room!']);
        }

        $ms = Bigbluebutton::isMeetingRunning($i->id);

        if ($ms == 1) {
            return redirect()->to(
                Bigbluebutton::join([
                    'meetingID' => $i->id,
                    'userName' => "Samji test",
                    'password' => $i->password_moderator //which user role want to join set password here
                ])
            );
        } else {
            $plan = PlanModel::where("id", 2)->first();
            if ($plan->recording) {
                $record = true; //overwrite default configuration
            } else {
                $record = false; //overwrite default configuration
            }

            $duration = $plan->duration;
            $max_user = $plan->participant;

            if ($i->muj) {
                $muj = true;
            } else {
                $muj = false;
            }

            if ($i->dpuc) {
                $dpuc = true;
            } else {
                $dpuc = false;
            }

            if ($i->dprc) {
                $dprc = true;
            } else {
                $dprc = false;
            }

            if ($i->ewma) {
                $ewma = true;
            } else {
                $ewma = false;
            }

            if ($i->dum) {
                $dum = true;
            } else {
                $dum = false;
            }

            if ($i->dsn) {
                $dsn = true;
            } else {
                $dsn = false;
            }

            if ($i->aujam) {
                $up = "moderator";
            } else {
                $up = $i->password_attendee;
            }

            if ($i->banner != "") {
                $banner = url('/') . "/roombanner/" . $i->banner;
            } else {
                $banner = "https://konn3ct.com/assets/images/konn3ct_logo.png";
            }

            $mdata['meeting_id'] = $i->id;
            $mdata['name'] = "samji via api";
            $mdata['email'] = "samjiviaapi@gmail.com";
            $mdata['password_attendee'] = $up;
            $mdata['status'] = "start meeting";
            $mdata['identifier'] = $i->id . rand();
            MeetingsModel::create($mdata);

            $url = Bigbluebutton::start([
                'meetingID' => "0$i->id",
                'moderatorPW' => $i->password_moderator, //moderator password set here
                'attendeePW' => $up, //attendee password here
                'meetingName' => $i->name,
                'userName' => "samji api",//for join meeting
                'endCallbackUrl' => url('/leftsession'),
                'logoutUrl' => url('/leftsession'),
                'welcomeMessage' => 'Welcome to <span style="color: #008b8b;"> konn3ct!</span><br><br> API Test',
//                'welcomeMessage'=> "Share this link with people you want in this meeting. <strong>". url('/join/')."/".$i->url."</strong>",
                'allowStartStopRecording' => $record,
                'record' => $record,
                'duration' => $duration,
                'maxParticipants' => $max_user,
                'muteOnStart' => $muj,
                'lockSettingsDisablePublicChat' => $dpuc,
                'lockSettingsDisablePrivateChat' => $dprc,
                'lockSettingsDisableCam' => $ewma,
                'lockSettingsDisableMic' => $dum,
                'lockSettingsDisableNote' => $dsn,
                'logo' => $banner,
                'avatarUrl' => 'https://dev.konn3ct.net/assets/images/konn3ctIcon.png',
                'customParameters' => [
                    'userdata-bbb_auto_join_audio' => 'true',
                    'userdata-bbb_enable_video' => 'true',
                    'userdata-bbb_listen_only_mode' => 'false',
                    'userdata-bbb_force_listen_only' => 'false',
                    'userdata-bbb_skip_check_audio' => 'true'
                ]
            ]);

            return response()->json(['success' => true, 'message' => 'Meeting started successfully.', 'url' => $url]);
        }

    }

    public function fetchRooms($email)
    {
        $u = User::where("email", $email)->first();

        if ($u == null) {
            return response()->json(['success' => false, 'message' => 'User does not exist']);
        }
        $rooms = RoomModel::where("user_id", $u->id)->get();

        return response()->json(['success' => true, 'message' => 'Rooms fetched successfully', 'data' => $rooms]);
    }

    /**
     * @param $timestamp
     * @return array
     */
    function generate_sec_key($timestamp = null): array
    {
        $timestamp = Carbon::now();
        $plaintext = intval(env("SMILE_PARTNER_ID")) . ":" . $timestamp;
        $hash_signature = hash('sha256', $plaintext);
        $sec_key = '';
        openssl_public_encrypt($hash_signature, $sec_key, base64_decode(env("SMILE_API_KEY")), OPENSSL_PKCS1_PADDING);
        $sec_key = base64_encode($sec_key);
        $sec_key = $sec_key . "|" . $hash_signature;
        return array("sec_key" => $sec_key, "timestamp" => $timestamp);
    }

    function confirm_sec_key($sec_key): bool
    {
        $sec_key_exploded = explode("|", $sec_key);
        $encrypted = base64_decode($sec_key_exploded[0]);
        $hash_signature = $sec_key_exploded[1];
        $decrypted = '';
        openssl_public_decrypt($encrypted, $decrypted, base64_decode($this->api_key), OPENSSL_PKCS1_PADDING);
        return $hash_signature == $decrypted;
    }

    /**
     * @param $timestamp
     * @return array
     */
    function generate_signature($timestamp = null): array
    {
        $timestamp = $timestamp != null ? $timestamp : Clock::now()->format(DateTimeInterface::ISO8601);
        $message = $timestamp . $this->partner_id . "sid_request";
        $sec_key = base64_encode(hash_hmac('sha256', $message, $this->api_key, true));
        return array("signature" => $sec_key, "timestamp" => $timestamp);
    }

    /**
     * @param $timestamp
     * @param string $signature
     * @return bool
     */
    function confirm_signature($timestamp, string $signature): bool
    {
        return $signature === $this->generate_signature($timestamp)["signature"];
    }

    /**
     * @param $timestamp
     * @return bool
     */
    private function isTimestamp($timestamp): bool
    {
        if (ctype_digit($timestamp) && strtotime(date('Y-m-d H:i:s', $timestamp)) === (int)$timestamp) {
            return true;
        } else {
            return false;
        }
    }
}
