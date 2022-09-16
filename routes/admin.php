<?php

use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\OtherController;
use App\Http\Controllers\admin\PaymentsController;
use App\Http\Controllers\admin\RecordingsController;
use App\Http\Controllers\admin\ResellerController;
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

        Route::get('/dashboard', [RoomsController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/coupon-codes', [CouponController::class, 'fetch'])->name('admin.coupon');
        Route::post('/coupon-codes', [CouponController::class, 'create'])->name('admin.coupon.create');
        Route::get('/disable-coupon-code/{id}', [CouponController::class, 'disable'])->name('admin.coupon.disable');
        Route::get('/enable-coupon-code/{id}', [CouponController::class, 'enable'])->name('admin.coupon.enable');

        Route::get('/rooms', [RoomsController::class, 'show'])->name('admin.rooms');

        Route::get('/meetings', [RoomsController::class, 'meetings'])->name('admin.meetings');

        Route::get('/meetings/{id}', [RoomsController::class, 'meetingsd'])->name('admin.meetingsd');

        Route::get('/invites', [UsersController::class, 'invites'])->name('admin.invites');

        Route::get('/users', [UsersController::class, 'show'])->name('admin.users');

        Route::get('/user/{id}', [UsersController::class, 'showUser'])->name('admin.user');

        Route::get('/generatereferralcode/{id}', [UsersController::class, 'generateReferralCode'])->name('admin.generateReferralCode');

        Route::post('/userupgrade', [UsersController::class, 'upgradeplan'])->name('admin.upgradeplan');

        Route::post('/apply-room-bundle', [UsersController::class, 'applyRoomBundle'])->name('admin.applyRoomBundle');

        Route::get('/referrals', [UsersController::class, 'referrals'])->name('admin.referrals');

        Route::get('/recording', [RecordingsController::class, 'show'])->name('admin.recordings');

        Route::get('/deleterecording', [RecordingsController::class, 'delete'])->name('admin.recording.delete');

        Route::get('/dashboard', [RoomController::class, 'show'])->name('admin.dashboard');

        Route::get('/payment', [PaymentsController::class, 'list'])->name('admin.payments');

        Route::get('/receipt/{id}', [PaymentsController::class, 'receipt'])->name('admin.receipt');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

        Route::get('/faqs', [OtherController::class, 'faqs'])->name('admin.faqs');

        Route::get('/resellers', [ResellerController::class, 'list'])->name('admin.resellers');

        Route::get('/resellers-users/{id}', [ResellerController::class, 'listUsers'])->name('admin.resellers-users');


    });
});
