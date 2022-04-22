<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\EnrolledChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    function fetchChats()
    {
        $chats = EnrolledChat::where('user_id', Auth::id())->with('room')->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $chats]);
    }

    function fetchParticipants($id)
    {
        $datas = EnrolledChat::where('room_id', $id)->with('user')->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    function fetchMessages($id)
    {
        $datas = EnrolledChat::where('room_id', $id)->with('messages', 'messages.user')->first();
        return response()->json(['success' => true, 'message' => 'Messages fetched successfully', 'data' => $datas]);
    }

    function sendMessage(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'message' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'errors' => $validator->errors()]);
        }

        $data = ChatMessage::create([
            'sender' => Auth::id(),
            'room_id' => $input['id'],
            'type' => "text",
            'message' => $input['message']
        ]);


        \App\Events\HealthEvent::dispatch($data);
        \App\Events\NewMessageEvent::dispatch($data);
//        broadcast(new ShippingStatusUpdated($update))->toOthers();

        return response()->json(['success' => true, 'message' => 'Message sent successfully', 'data' => $data]);
    }

    function enrol2Chat(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'user_id' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'errors' => $validator->errors()]);
        }

        $check = EnrolledChat::where(['user_id' => $input['user_id'] ?? Auth::id(), 'room_id' => $input['id']])->first();

        if ($check) {
            return response()->json(['success' => false, 'message' => 'You are already a participant']);
        }

        if (isset($input['user_id'])) {
            $check2 = EnrolledChat::where(['user_id' => Auth::id(), 'room_id' => $input['id']])->first();

            if (!$check2) {
                return response()->json(['success' => false, 'message' => 'You are not part of this chat']);
            }

            if ($check2->owner != 1) {
                return response()->json(['success' => false, 'message' => 'Only owner can invite people to chat']);
            }

            $data = EnrolledChat::create([
                'user_id' => $input['user_id'],
                'room_id' => $input['id']
            ]);
        } else {
            $data = EnrolledChat::create([
                'user_id' => Auth::id(),
                'room_id' => $input['id']
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Enrolled successfully', 'data' => $data]);
    }
}
