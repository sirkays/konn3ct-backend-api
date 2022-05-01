<?php

namespace App\Http\Controllers\Api\App;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\EnrolledChat;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    function fetchChats()
    {
        $chats = EnrolledChat::where('user_id', Auth::id())->with('room', 'lastMessage.user')->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $chats]);
    }

    function fetchParticipants($id)
    {
        $datas = EnrolledChat::where('room_id', $id)->with('user')->get();
        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    function fetchMessages($id)
    {

        $check = EnrolledChat::where(['user_id' => Auth::id(), 'room_id' => $id])->first();

        if (!$check) {
            return response()->json(['success' => false, 'message' => 'You are not a participant']);
        }

        $datas = EnrolledChat::where('room_id', $id)->with('messages', 'messages.user')->first();
        return response()->json(['success' => true, 'message' => 'Messages fetched successfully', 'data' => $datas]);
    }

    function deleteMessage($id)
    {
        $data = ChatMessage::find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Message does not exist']);
        }

        if ($data->sender != Auth::id() && $data->room->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You dont have access to delete this message']);
        }
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Message deleted']);
    }

    function sendMessage(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'message' => 'required',
            'type' => 'required',
            'reply' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'errors' => $validator->errors()]);
        }

        $check = EnrolledChat::where(['user_id' => Auth::id(), 'room_id' => $input['id']])->first();

        if (!$check) {
            return response()->json(['success' => false, 'message' => 'You are not a participant']);
        }


        if (isset($input['reply'])) {
            $replyChat = ChatMessage::where(['id' => $input['reply'], 'room_id' => $input['id']])->first();
            if (!$replyChat) {
                return response()->json(['success' => false, 'message' => 'Message does not exist']);
            }
        }

        if ($input['type'] == "image") {

            $image = $input["message"];
            $photo = $input['id'] . Auth::id() . "_" . rand() . ".jpg";

            $message = "chat_images/" . $photo;

            $decodedImage = base64_decode("$image");
            file_put_contents(storage_path($message), $decodedImage);

            $data = ChatMessage::create([
                'sender' => Auth::id(),
                'room_id' => $input['id'],
                'type' => "image",
                'message' => $message
            ]);
        }else if ($input['type'] == "audio") {

            $image = $input["message"];
            $photo = $input['id'] . Auth::id() . "_" . rand() . ".mp4";

            $message = "chat_audio/" . $photo;

            $decodedImage = base64_decode("$image");
            file_put_contents(storage_path($message), $decodedImage);

            $data = ChatMessage::create([
                'sender' => Auth::id(),
                'room_id' => $input['id'],
                'type' => "image",
                'message' => $message
            ]);
        } else {
            $data = ChatMessage::create([
                'sender' => Auth::id(),
                'room_id' => $input['id'],
                'type' => "text",
                'message' => $input['message']
            ]);
        }


        NewMessageEvent::dispatch($data);
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

        return response()->json(['success' => true, 'message' => 'Enrolled successfully', 'data' => $data, 'room' => $data->room]);
    }

    function unenrol2Chat(Request $request)
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

        if (!$check) {
            return response()->json(['success' => false, 'message' => 'You are not a participant']);
        }

        $check->delete();

        return response()->json(['success' => true, 'message' => 'Room left successfully']);
    }

    function autoProcessEnrolment(Request $request)
    {
        $allRooms = RoomModel::get();

        foreach ($allRooms as $room) {
            if ($room->user_id != NULL) {
                $check = EnrolledChat::where(['user_id' => $room->user_id, 'room_id' => $room->id])->exists();

                if (!$check) {
                    EnrolledChat::create([
                        'user_id' => $room->user_id,
                        'room_id' => $room->id,
                        'owner' => 1
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Enrolled successfully']);
    }
}
