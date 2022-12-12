<?php

use App\Http\Controllers\Api\App\AuthController;
use App\Http\Controllers\Api\App\ChatController;
use App\Http\Controllers\Api\App\RoomsController;
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

Route::group(['prefix' => 'app'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password-request', [AuthController::class, 'reset_password_request']);
    Route::post('forgot-password', [AuthController::class, 'reset_password_submit']);
    Route::post('verify-code', [AuthController::class, 'verifyCode']);
    Route::post('validate-meeting', [AuthController::class, 'validateMeeting']);
    Route::post('join-room', [RoomsController::class, 'joinAppRoom']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('chats', [ChatController::class, 'fetchChats']);
        Route::get('chats/participants/{id}', [ChatController::class, 'fetchParticipants']);
        Route::get('chats/messages/{id}', [ChatController::class, 'fetchMessages']);
        Route::delete('chats/message/delete/{id}', [ChatController::class, 'deleteMessage']);
        Route::post('chat', [ChatController::class, 'sendMessage']);
        Route::post('chat/enroll', [ChatController::class, 'enrol2Chat']);
        Route::post('chat/unenroll', [ChatController::class, 'unenrol2Chat']);
        Route::post('validate-user', [ChatController::class, 'validateUser']);
        Route::post('validate-phones', [ChatController::class, 'validatePhones']);
        Route::post('whatsapp/inviteAll', [RoomsController::class, 'inviteAll']);
    });
});


