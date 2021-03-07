<?php

use App\Http\Controllers\admin\PaymentsController;
use App\Http\Controllers\admin\RecordingsController;
use App\Http\Controllers\admin\RoomsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\ContactController;
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

Route::get('/offline', function () {
    return view('vendor/laravelpwa/offline');
});

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

Route::get('/konn3ct', function () {
    return view('konn3ct_session');
});

Route::get('/leftsession', function () {
    return view('left_session');
});

Route::get('/join/{url}', function ($url) {
    return view('join_session', ['url'=>$url]);
});

Route::post('/ajoinroom', [RoomController::class, 'ajoin'])->name('attendee_join');

Route::post('/konn3ct', [RoomController::class, 'fjoin'])->name('konn3ct');

Route::get('/roomstatus/{url}', [RoomController::class, 'roomstatus'])->name('roomstatus');

Route::get('/features', function () {
    return view('features');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::post('/contact', [ContactController::class, 'index'])->name('contactsent');

Route::get('/roombanner/{filename}', function ($filename)
{
    $path = storage_path('roombanner/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->name('show.roombanner');



Route::middleware(['auth:sanctum', 'verified', 'NewUserPlanCheck', 'checksub'])->group(function () {

    Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');
    Route::post('/joinroom', [RoomController::class, 'mjoin'])->name('moderator_join');
    Route::post('/deleteroom', [RoomController::class, 'delete'])->name('delete');

    Route::get('/room', [RoomController::class, 'show'])->name('room');

    Route::get('/dashboard', [RoomController::class, 'show'])->name('dashboard');

    Route::get('/activateft', [PaymentController::class, 'activatefree'])->name('activatefree');

    Route::get('/payment', [PaymentController::class, 'list'])->name('payments');

    Route::get('/receipt', [PaymentController::class, 'receipt'])->name('receipt');

    Route::get('/exportreceipt', [PaymentController::class, 'exportreceipt'])->name('exportreceipt');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/user/profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/recording', [RecordingController::class, 'show'])->name('recording');

    Route::post('/deleterecording', [RecordingController::class, 'delete'])->name('recording.delete');

    Route::post('/invite', [RoomController::class, 'invite'])->name('invite');

    Route::post('/accesscode', [RoomController::class, 'accesscode'])->name('accesscode');

    Route::post('/limituser', [RoomController::class, 'limituser'])->name('limituser');

    Route::post('/bannerupload', [RoomController::class, 'bannerupload'])->name('bannerupload');

    Route::get('/welcomemail', function (){
        return (new \App\Mail\UserWelcomeMail())->render();
    })->name('mailtest');

Route::get('/invitemail', function (){
        $data['ihost']="Samji";

        $data['ilink']=url('/join/')."login";

        $data['idate']="2020-12";

        $data['iaccesscode']="hello";

        $data['itime']="12:40";

        $data['iroom']="Sammy Room";

        return (new \App\Mail\UserWelcomeMail($data))->render();
    })->name('mailtest');

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/pay', function () {
        return view('payment');
    })->name('payment');

//    Route::get('/payment/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/payment/{plan}/transid/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/changeplan/{plan}', [PaymentController::class, 'changeplan'])->name('changeplan');

    Route::get('/logouts', [AuthenticatedSessionController::class, 'destroy'])->name('logouts');

});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {

        Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');
        Route::post('/joinroom', [RoomController::class, 'mjoin'])->name('moderator_join');
        Route::post('/deleteroom', [RoomController::class, 'delete'])->name('delete');

        Route::get('/rooms', [RoomsController::class, 'show'])->name('admin.rooms');

        Route::get('/meetings', [RoomsController::class, 'meetings'])->name('admin.meetings');

        Route::get('/meetings/{id}', [RoomsController::class, 'meetingsd'])->name('admin.meetingsd');

        Route::get('/users', [UsersController::class, 'show'])->name('admin.users');

        Route::get('/user/{id}', [UsersController::class, 'showUser'])->name('admin.user');

        Route::get('/recording', [RecordingsController::class, 'show'])->name('admin.recordings');

        Route::get('/deleterecording', [RecordingsController::class, 'delete'])->name('admin.recordings.delete');

        Route::get('/dashboard', [RoomController::class, 'show'])->name('admin.dashboard');

        Route::get('/payment', [PaymentsController::class, 'list'])->name('admin.payments');

        Route::get('/receipt/{id}', [PaymentsController::class, 'receipt'])->name('admin.receipt');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');


    });
});
