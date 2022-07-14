<?php


use App\Http\Controllers\Api\Integration\MeetingController;
use App\Http\Controllers\Api\Integration\PlansController;
use App\Http\Controllers\Api\Integration\RoomController;
use App\Http\Controllers\Api\Integration\UserController;
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

Route::group(['prefix' => 'integration'], function () {
    Route::get('getUsers', [UserController::class, 'userList']);
    Route::get('getUsersPlan/{plan}', [UserController::class, 'userListByPlan']);
    Route::get('getRooms', [RoomController::class, 'roomList']);
    Route::get('getMeetings', [MeetingController::class, 'meetingList']);
    Route::get('getPlans', [PlansController::class, 'planList']);
});
