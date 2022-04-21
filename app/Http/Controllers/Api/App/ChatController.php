<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\EnrolledChat;
use Illuminate\Support\Facades\Auth;

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
}
