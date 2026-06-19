<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeamsController extends Controller
{
    public function invite(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required|in:Owner,Admin,Member',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(',', $validator->errors()->all()), 'errors' => $validator->errors()], 422);
        }

        $existingInvite = TeamMember::where('team_owner_id', Auth::id())->where('email', $request->email)->first();
        if ($existingInvite) {
            return response()->json(['success' => false, 'message' => 'Email already invited to team'], 400);
        }

        $activationToken = Str::random(40);

        $teamMember = TeamMember::create([
            'team_owner_id' => Auth::id(),
            'email' => $request->email,
            'role' => $request->role,
            'status' => 'pending',
            'activation_token' => $activationToken,
        ]);

        $activationLink = url('/api/app/teams/activate/' . $activationToken);
        Mail::to($request->email)->later(now(), new \App\Mail\TeamInviteMail($activationLink));

        return response()->json(['success' => true, 'message' => 'Invite sent successfully', 'data' => $teamMember], 201);
    }

    public function activate($token)
    {
        $teamMember = TeamMember::where('activation_token', $token)->where('status', 'pending')->first();
        if (!$teamMember) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired activation token'], 400);
        }

        $user = User::where('email', $teamMember->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found, please register first'], 400);
        }

        $teamMember->update([
            'user_id' => $user->id,
            'status' => 'active',
            'activation_token' => null,
            'activated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Team membership activated successfully', 'data' => $teamMember]);
    }

    
    public function checkToken($token)
    {
        $teamMember = TeamMember::where('activation_token', $token)->where('status', 'pending')->first();
        if (!$teamMember) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired activation token'], 400);
        }

        $user = User::where('email', $teamMember->email)->select('email', 'firstname', 'lastname')->first();

        return response()->json(['success' => true, 'message' => 'Team membership validated successfully', 'data' => [
            'team_member' => $teamMember,
            'user' => $user,
        ]]);
    }


    public function list()
    {
        $teamMembers = TeamMember::where('team_owner_id', Auth::id())->with('user')->get();
        return response()->json(['success' => true, 'message' => 'Team members fetched successfully', 'data' => $teamMembers]);
    }

    public function updateRole(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'role' => 'required|in:Owner,Admin,Member',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(',', $validator->errors()->all()), 'errors' => $validator->errors()], 422);
        }

        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember) {
            return response()->json(['success' => false, 'message' => 'Team member not found'], 404);
        }

        $teamMember->update(['role' => $request->role]);

        return response()->json(['success' => true, 'message' => 'Role updated successfully', 'data' => $teamMember]);
    }

    public function remove($id)
    {
        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember) {
            return response()->json(['success' => false, 'message' => 'Team member not found'], 404);
        }

        $teamMember->delete();

        return response()->json(['success' => true, 'message' => 'Team member removed successfully']);
    }

    public function suspend($id)
    {
        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember) {
            return response()->json(['success' => false, 'message' => 'Team member not found'], 404);
        }

        $teamMember->update(['status' => 'suspended']);

        return response()->json(['success' => true, 'message' => 'Team member suspended successfully', 'data' => $teamMember]);
    }

    public function unsuspend($id)
    {
        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember) {
            return response()->json(['success' => false, 'message' => 'Team member not found'], 404);
        }

        $teamMember->update(['status' => 'active']);

        return response()->json(['success' => true, 'message' => 'Team member unsuspended successfully', 'data' => $teamMember]);
    }

    public function resetPassword(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(',', $validator->errors()->all()), 'errors' => $validator->errors()], 422);
        }

        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember || !$teamMember->user_id) {
            return response()->json(['success' => false, 'message' => 'Team member not found or not activated'], 404);
        }

        $user = User::find($teamMember->user_id);
        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['success' => true, 'message' => 'Password reset successfully']);
    }

    public function viewProfile($id)
    {
        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->with('user')->first();
        if (!$teamMember || !$teamMember->user_id) {
            return response()->json(['success' => false, 'message' => 'Team member not found or not activated'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Profile fetched successfully', 'data' => $teamMember->user]);
    }

    public function viewRooms($id)
    {
        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember || !$teamMember->user_id) {
            return response()->json(['success' => false, 'message' => 'Team member not found or not activated'], 404);
        }

        $rooms = \App\Models\RoomModel::where('user_id', $teamMember->user_id)->get();
        return response()->json(['success' => true, 'message' => 'Rooms fetched successfully', 'data' => $rooms]);
    }

    public function viewRecordings($id)
    {
        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember || !$teamMember->user_id) {
            return response()->json(['success' => false, 'message' => 'Team member not found or not activated'], 404);
        }

        $recordings = \App\Models\Recording::where('user_id', $teamMember->user_id)->get();
        return response()->json(['success' => true, 'message' => 'Recordings fetched successfully', 'data' => $recordings]);
    }

    public function viewSessions($id)
    {
        $teamMember = TeamMember::where('id', $id)->where('team_owner_id', Auth::id())->first();
        if (!$teamMember || !$teamMember->user_id) {
            return response()->json(['success' => false, 'message' => 'Team member not found or not activated'], 404);
        }

        $sessions = \App\Models\MeetingsModel::where('email', $teamMember->user->email)->get();
        return response()->json(['success' => true, 'message' => 'Sessions fetched successfully', 'data' => $sessions]);
    }
}
