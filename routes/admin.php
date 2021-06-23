<?php

use App\Http\Controllers\admin\PaymentsController;
use App\Http\Controllers\admin\RecordingsController;
use App\Http\Controllers\admin\RoomsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {

        Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');
        Route::post('/joinroom', [RoomController::class, 'mjoin'])->name('moderator_join');
        Route::post('/deleteroom', [RoomController::class, 'delete'])->name('delete');

        Route::get('/rooms', [RoomsController::class, 'show'])->name('admin.rooms');

        Route::get('/meetings', [RoomsController::class, 'meetings'])->name('admin.meetings');

        Route::get('/meetings/{id}', [RoomsController::class, 'meetingsd'])->name('admin.meetingsd');

        Route::get('/invites', [UsersController::class, 'invites'])->name('admin.invites');

        Route::get('/users', [UsersController::class, 'show'])->name('admin.users');

        Route::get('/user/{id}', [UsersController::class, 'showUser'])->name('admin.user');

        Route::post('/userupgrade', [UsersController::class, 'upgradeplan'])->name('admin.upgradeplan');

        Route::get('/referrals', [UsersController::class, 'referrals'])->name('admin.referrals');

        Route::get('/recording', [RecordingsController::class, 'show'])->name('admin.recordings');

        Route::get('/deleterecording', [RecordingsController::class, 'delete'])->name('admin.recording.delete');

        Route::get('/dashboard', [RoomController::class, 'show'])->name('admin.dashboard');

        Route::get('/payment', [PaymentsController::class, 'list'])->name('admin.payments');

        Route::get('/receipt/{id}', [PaymentsController::class, 'receipt'])->name('admin.receipt');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');


    });
});
