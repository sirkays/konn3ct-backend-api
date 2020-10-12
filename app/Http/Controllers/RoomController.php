<?php

namespace App\Http\Controllers;

use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JoisarJignesh\Bigbluebutton\Bigbluebutton;

class RoomController extends Controller
{
    public function create(Request $request){
        $input=$request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'url' => 'nullable|unique:room',
            'dial_number' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($input['url']==""){
            $num=Auth::user()->name.date('siyhy');
            $shuffled = str_shuffle($num);
            $sfinal=substr($shuffled, 0, 8);

            $input['url']=$sfinal;
        }

        $input['user_id']=Auth::id();
        $input['password_attendee']="";
        $input['password_moderator']="";
        $input['welcome_message']="";
        $input['logout_url']="";
        $input['max_participants']=0;
        $input['duration']=0;

        $r=RoomModel::create($input);


        /* _____ PHP Big Blue Button API Usage ______
        * by Peter Mentzer peter@petermentzerdesign.com
        * Use, modify and distribute however you like.
        */

// Require the bbb-api file:
        require_once(storage_path().'/bbb-api-php/includes/bbb-api.php');


// Instatiate the BBB class:
        $bbb = new BigBlueButton();

        /* ___________ CREATE MEETING w/ OPTIONS ______ */
        /*
        */
        $creationParams = array(
            'meetingId' => $r->id, 					// REQUIRED
            'meetingName' => $input['room'], 	// REQUIRED
            'attendeePw' => 'ap', 					// Match this value in getJoinMeetingURL() to join as attendee.
            'moderatorPw' => 'mp', 					// Match this value in getJoinMeetingURL() to join as moderator.
            'welcomeMsg' => '', 					// ''= use default. Change to customize.
            'dialNumber' => '', 					// The main number to call into. Optional.
            'voiceBridge' => '', 					// PIN to join voice. Optional.
            'webVoice' => '', 						// Alphanumeric to join voice. Optional.
            'logoutUrl' => '', 						// Default in bigbluebutton.properties. Optional.
            'maxParticipants' => '-1', 				// Optional. -1 = unlimitted. Not supported in BBB. [number]
            'record' => 'false', 					// New. 'true' will tell BBB to record the meeting.
            'duration' => '0', 						// Default = 0 which means no set duration in minutes. [number]
            //'meta_category' => '', 				// Use to pass additional info to BBB server. See API docs.
        );

// Create the meeting and get back a response:
        $itsAllGood = true;
        try {$result = $bbb->createMeetingWithXmlResponseArray($creationParams);}
        catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            $itsAllGood = false;
        }

        if ($itsAllGood == true) {
            // If it's all good, then we've interfaced with our BBB php api OK:
            if ($result == null) {
                // If we get a null response, then we're not getting any XML back from BBB.
                echo "Failed to get any response. Maybe we can't contact the BBB server.";
            }
            else {
                // We got an XML response, so let's see what it says:
                print_r($result);
                if ($result['returncode'] == 'SUCCESS') {
                    // Then do stuff ...
                    echo "<p>Meeting succesfullly created.</p>";
                }
                else {
                    echo "<p>Meeting creation failed.</p>";
                }
            }
        }

//        return redirect('room')->with('success', 'Room Created Successfully!');
    }

    public function show(){

        echo \Bigbluebutton::isConnect(); //default

        return \Bigbluebutton::create([
            'meetingID' => 'tamku',
            'meetingName' => 'test meeting',
            'attendeePW' => 'attendee',
            'moderatorPW' => 'moderator'
        ]);

//        $datas['rooms']=RoomModel::where("user_id", Auth::id())->orderBy('id', 'desc')->get();
//        $datas['roomstc']=RoomModel::where("user_id", Auth::id())->count();
//        return view('user.dashboard', $datas);
    }
}
