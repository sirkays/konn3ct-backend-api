<?php

use App\Http\Controllers\Api\App\AuthController;
use App\Http\Controllers\Api\App\CallController;
use App\Http\Controllers\Api\App\ChatController;
use App\Http\Controllers\Api\App\DonationController;
use App\Http\Controllers\Api\App\InviteController;
use App\Http\Controllers\Api\App\KycController;
use App\Http\Controllers\Api\App\MeetingController;
use App\Http\Controllers\Api\App\ProfileController;
use App\Http\Controllers\Api\App\RecordingController;
use App\Http\Controllers\Api\App\RoomsController;
use App\Http\Controllers\Api\App\StreamingController;
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
    Route::post('login-social', [AuthController::class, 'loginSocial']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password-request', [AuthController::class, 'reset_password_request']);
    Route::post('forgot-password', [AuthController::class, 'reset_password_submit']);
    Route::post('verify-code', [AuthController::class, 'verifyCode']);

    Route::post('validate-meeting', [AuthController::class, 'validateMeeting']);
    Route::post('join-room', [RoomsController::class, 'joinAppRoom']);
    Route::post('kv4/join-room', [RoomsController::class, 'joinRoomkv4']);
    Route::post('kv4/validate-meeting', [AuthController::class, 'validateMeetingkv4']);

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
        Route::post('call/initiate', [CallController::class, 'initiateCall']);
        Route::post('call/update', [CallController::class, 'updateCall']);
        Route::get('call/list', [CallController::class, 'listCalls']);

        Route::post('/createroom', [RoomsController::class, 'create']);
        Route::delete('/deleteroom/{id}', [RoomsController::class, 'delete']);
        Route::get('/myrooms', [RoomsController::class, 'show']);
        Route::get('start-a-room/{id}', [RoomsController::class, 'mStartRoom']);
        Route::post('accesscode', [RoomsController::class, 'accesscode']);
        Route::post('limituser', [RoomsController::class, 'limituser']);
        Route::post('bannerupload', [RoomsController::class, 'bannerupload']);
        Route::post('transfer-room', [RoomsController::class, 'transferRoom']);


        Route::get('upcoming-meetings', [MeetingController::class, 'upcomingMeetings']);

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
        Route::put('/update-password', [ProfileController::class, 'updatePassword']);
        Route::get('/user-sessions', [ProfileController::class, 'viewSessions']);
        Route::delete('/user-sessions-delete-others', [ProfileController::class, 'deleteOthersUserSession']);
        Route::get('/payments', [ProfileController::class, 'payments']);
        Route::get('/pricing-plans', [ProfileController::class, 'plans']);
        Route::get('/addons', [ProfileController::class, 'addons']);
        Route::get('/referee', [ProfileController::class, 'referee']);

        Route::post('/start-streaming', [StreamingController::class, 'startStreaming']);
        Route::get('/stop-streaming/{id}', [StreamingController::class, 'stopStreaming']);
        Route::get('/streamings', [StreamingController::class, 'list']);

        Route::get('/recording', [RecordingController::class, 'show']);
        Route::delete('/deleterecording/{id}', [RecordingController::class, 'delete']);

        Route::post('/invite', [InviteController::class, 'invite'])->name('invite');
        Route::get('/invites', [InviteController::class, 'invites'])->name('invites');
        Route::put('/resendinvites/{id}', [InviteController::class, 'resendinvite'])->name('resendinvites');

        Route::get('/kyc/fetch', [KycController::class, 'fetchKyc']);
        Route::get('/banks', [KycController::class, 'banks']);
        Route::post('/verify-bank', [KycController::class, 'verifyBank']);
        Route::post('/kyc/submit', [KycController::class, 'individualKyc']);
        Route::post('/kyc-corporate/submit', [KycController::class, 'corporateKyc']);

        Route::get('/donation/stats', [DonationController::class, 'stats']);
        Route::get('/donation/in', [DonationController::class, 'donationsIn']);

    });
});


