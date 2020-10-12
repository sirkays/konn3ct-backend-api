<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pricing', function () {
    return view('pricing');
});

Route::get('/joinsession', function () {
    return view('join_session');
});

Route::get('/features', function () {
    return view('features');
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');

    Route::get('/room', [RoomController::class, 'show'])->name('room');

    Route::get('/dashboard', [RoomController::class, 'show'])->name('room');

    Route::get('/invoice', function () {
        return view('user.invoice');
    })->name('invoice');

    Route::get('/profile', function () {
        return view('user.profile');
    })->name('profile');

    Route::get('/recording', function () {
        return view('user.recording');
    })->name('recording');

});
