<?php

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

Route::post('paystackhook', [PaystackHookController::class, 'index']);

Route::post('register', [UserController::class, 'createUser']);

Route::get('rooms/{email}', [RoomController::class, 'fetchRooms']);

Route::get('start-a-room/{id}', [RoomController::class, 'startaRoom']);

Route::post('start-room', [RoomController::class, 'startRoom']);

Route::post('check-room', [RoomController::class, 'checkRoom']);
