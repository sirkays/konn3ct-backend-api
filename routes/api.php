<?php

use App\Http\Controllers\Api\App\ChatController;
use App\Http\Controllers\Api\App\PreregistrationController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DeployController;
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

Route::get('/enter', function (Request $request) {

    $token = $request->get('sessionToken');

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://meet3.konn3ct.com/bigbluebutton/api/enter?sessionToken=$token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('deploy', [DeployController::class, 'deploy']);

Route::get('enrolAll', [ChatController::class, 'autoProcessEnrolment']);

Route::post('create-token', [UserController::class, 'createToken']);

Route::post('register', [UserController::class, 'createUser']);

Route::get('rooms/{email}', [RoomController::class, 'fetchRooms']);

Route::post('start-room0', [RoomController::class, 'startRoom']);

Route::post('check-room', [RoomController::class, 'checkRoom']);

Route::get('pre-registration/{reference}', [PreregistrationController::class, 'preregshow']);
Route::post('pre-registration-search', [PreregistrationController::class, 'preregshowSearch']);
Route::post('pre-registration', [PreregistrationController::class, 'registerprereg']);


Route::apiResource('k4/donation', DonationController::class);
Route::post('k4/donation/pay/{donation}', [DonationController::class, 'pay']);
Route::get('k4/donation/ref/{ref}', [DonationController::class, 'paymentCheck']);


Route::group(['middleware' => 'resellerAuth', 'prefix' => 'reseller'], function () {
    Route::get('activity/country/{countrycode}', [\App\Http\Controllers\Api\PricingController::class, 'getActivity']);
    Route::get('users/{id}', [\App\Http\Controllers\Api\PricingController::class, 'getUsers']);
    Route::get('pricing/{currency}', [\App\Http\Controllers\Api\PricingController::class, 'getPlans']);
    Route::post('user/register', [\App\Http\Controllers\Api\PricingController::class, 'register']);
    Route::post('business/register', [\App\Http\Controllers\Api\PricingController::class, 'business']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('list-rooms', [RoomController::class, 'listRooms']);
    Route::get('list-rooms-withstatus', [RoomController::class, 'listRoomswStatus']);
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

    Route::delete('delete-room/{id}', [RoomController::class, 'deleteRoom']);
    Route::get('start-a-room/{id}', [RoomController::class, 'startaRoom']);
    Route::get('room-status/{id}', [RoomController::class, 'roomStatus']);
});


require __DIR__ . '/api_integration.php';
require __DIR__ . '/app.php';
require __DIR__ . '/hook.php';

Route::prefix('v1/admin/auth')->group(function () {
    Route::post('login', [\App\Http\Controllers\Api\V1\Admin\AuthController::class, 'login'])
        ->name('api.v1.admin.auth.login');
    Route::post('refresh', [\App\Http\Controllers\Api\V1\Admin\AuthController::class, 'refresh'])
        ->name('api.v1.admin.auth.refresh');
});

// ── Protected Admin API routes ──────────────────────────────────────────────
// All routes in this group require a valid Admin JWT access token.
// Login and Refresh remain public above.
Route::prefix('v1/admin')
    ->middleware(['admin.jwt'])
    ->group(function () {

    // User Management — requires users:read permission
    Route::get('users', [\App\Http\Controllers\Api\V1\Admin\UserManagementController::class, 'index'])
        ->middleware('admin.permission:users:read')
        ->name('api.v1.admin.users.index');

    // User Suspension — requires users:suspend permission
    Route::post('users/{id}/suspend', [\App\Http\Controllers\Api\V1\Admin\UserManagementController::class, 'suspend'])
        ->middleware('admin.permission:users:suspend')
        ->name('api.v1.admin.users.suspend')
        ->where('id', '[0-9]+');

    // User Ban — requires users:ban permission
    Route::post('users/{id}/ban', [\App\Http\Controllers\Api\V1\Admin\UserManagementController::class, 'ban'])
        ->middleware('admin.permission:users:ban')
        ->name('api.v1.admin.users.ban')
        ->where('id', '[0-9]+');

    // Financial Transactions — requires financials:read permission
    Route::get('financials/transactions', [\App\Http\Controllers\Api\V1\Admin\FinancialsController::class, 'transactions'])
        ->middleware('admin.permission:financials:read')
        ->name('api.v1.admin.financials.transactions');
});

