<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

//Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
//    $check = \App\Models\EnrolledChat::where(['user_id' => $user->id, 'room_id' => $roomId])->first();
//    if ($check) {
//        return ['id' => $user->id, 'name' => $user->name];
//    }
//    return false;
//}, ['middleware' => 'websocket']);

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    return true;
}, ['middleware' => 'auth:sanctum']);
