<?php

use App\Http\Controllers\Api\App\AuthController;
use App\Http\Controllers\Api\App\ChatController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DeployController;
use App\Http\Controllers\PaystackHookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('deploy', [DeployController::class, 'deploy']);

Route::get('enrolAll', [ChatController::class, 'autoProcessEnrolment']);

Route::post('paystackhook', [PaystackHookController::class, 'index']);

Route::post('register', [UserController::class, 'createUser']);

Route::get('rooms/{email}', [RoomController::class, 'fetchRooms']);

Route::post('start-room0', [RoomController::class, 'startRoom']);

Route::post('check-room', [RoomController::class, 'checkRoom']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('list-rooms', [RoomController::class, 'listRooms']);
    Route::get('room-recordings/{id}', [RoomController::class, 'roomRecordings']);
    Route::get('rooms-recordings', [RoomController::class, 'allRecordings']);
    Route::get('room-details/{id}', [RoomController::class, 'roomInfo']);
    Route::post('meeting-info', [RoomController::class, 'meetingInfo']);
    Route::get('meeting-history', [RoomController::class, 'meetingHistory']);
    Route::get('list-attendance/{id}', [RoomController::class, 'listAttendance']);
    Route::get('attendance-details/{id}/{identifier}', [RoomController::class, 'attendanceDetails']);

    Route::post('start-room', [RoomController::class, 'startRoom']);
    Route::post('join-room', [RoomController::class, 'joinRoom']);
    Route::post('create-room', [RoomController::class, 'createRoom']);

    Route::get('start-a-room/{id}', [RoomController::class, 'startaRoom']);
});

Route::group(['prefix' => 'app'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password-request', [AuthController::class, 'reset_password_request']);
    Route::post('forgot-password', [AuthController::class, 'reset_password_submit']);
    Route::post('verify-code', [AuthController::class, 'verifyCode']);
    Route::post('validate-meeting', [AuthController::class, 'validateMeeting']);
    Route::post('join-room', [RoomController::class, 'joinAppRoom']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('chats', [ChatController::class, 'fetchChats']);
        Route::get('chats/participants/{id}', [ChatController::class, 'fetchParticipants']);
        Route::get('chats/messages/{id}', [ChatController::class, 'fetchMessages']);
        Route::delete('chats/message/delete/{id}', [ChatController::class, 'deleteMessage']);
        Route::post('chat', [ChatController::class, 'sendMessage']);
        Route::post('chat/enroll', [ChatController::class, 'enrol2Chat']);
        Route::post('chat/unenroll', [ChatController::class, 'unenrol2Chat']);
    });
});
