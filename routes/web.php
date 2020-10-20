<?php

use App\Http\Controllers\admin\RecordingsController;
use App\Http\Controllers\admin\RoomsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\MyAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register/{id}', [MyAuthController::class, 'register']);

Route::get('/pricing', function () {
    return view('pricing');
});

Route::get('/joinsession', function () {
    return view('join_session');
});

Route::get('/join/{url}', function ($url) {
    return view('join_session', ['url'=>$url]);
});

Route::post('/ajoinroom', [RoomController::class, 'ajoin'])->name('attendee_join');

Route::get('/features', function () {
    return view('features');
});

Route::get('/contact', function () {
    return view('contact');
});


Route::middleware(['auth:sanctum', 'verified', 'NewUserPlanCheck'])->group(function () {

    Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');
    Route::post('/joinroom', [RoomController::class, 'mjoin'])->name('moderator_join');
    Route::post('/deleteroom', [RoomController::class, 'delete'])->name('delete');

    Route::get('/room', [RoomController::class, 'show'])->name('room');

    Route::get('/dashboard', [RoomController::class, 'show'])->name('dashboard');

    Route::get('/payment', [PaymentController::class, 'receipt'])->name('payments');

//    Route::get('/receipt', [PaymentController::class, 'receipt'])->name('invoice');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/recording', [RecordingController::class, 'show'])->name('recording');

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/pay', function () {
        return view('payment');
    })->name('payment');

//    Route::get('/payment/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/payment/{plan}/transid/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/changeplan/{plan}', [PaymentController::class, 'changeplan'])->name('changeplan');

    Route::get('/logouts', [AuthenticatedSessionController::class, 'destroy']
    )->name('logouts');

});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {

        Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');
        Route::post('/joinroom', [RoomController::class, 'mjoin'])->name('moderator_join');
        Route::post('/deleteroom', [RoomController::class, 'delete'])->name('delete');

        Route::get('/rooms', [RoomsController::class, 'show'])->name('admin.rooms');

        Route::get('/users', [UsersController::class, 'show'])->name('admin.users');

        Route::get('/recording', [RecordingsController::class, 'show'])->name('admin.recordings');

        Route::get('/dashboard', [RoomController::class, 'show'])->name('dashboard');

        Route::get('/payment', [PaymentController::class, 'receipt'])->name('payments');

//    Route::get('/receipt', [PaymentController::class, 'receipt'])->name('invoice');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');


    });
});
