<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Jobs\PushNotificationCallJob;
use App\Models\CallLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CallController extends Controller
{

    function initiateCall(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $check = User::where('id', $input['user'])->orwhere('email', $input['user'])->orwhere('phone', $input['user'])->select('id', 'firstname', 'lastname', 'phone', 'email')->first();

        if (!$check) {
            return response()->json(['success' => false, 'message' => 'User does not exist']);
        }

        if ($check->id == Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You can not call yourself']);
        }

        CallLog::create([
            "user_id" => Auth::id(),
            "status" => "outgoing",
            "type" => $input['type'],
        ]);

        $c = CallLog::create([
            "user_id" => $check->id,
            "status" => "incoming",
            "type" => $input['type'],
        ]);

        PushNotificationCallJob::dispatch($check->id, Auth::user());

        return response()->json(['success' => true, 'message' => 'Call registered successfully', 'data' => $check, 'callee' => $c]);
    }

    function updateCall(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $call = CallLog::find($input['id']);
        $call->status = $input['status'];
        $call->save();

        return response()->json(['success' => true, 'message' => 'Call Updated successfully', 'data' => $call]);
    }

    function listCalls(Request $request)
    {

        $call = CallLog::where('user_id', Auth::id())->get();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $call]);
    }
}
