<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MasterCardGatewayController;
use App\Http\Controllers\MyAuthController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\RoomController;
use App\Mail\UserWelcomeMail;
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
})->name('welcome');

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
    return view('join_session', ['url' => $url]);
});

Route::post('/ajoinroom', [RoomController::class, 'ajoin'])->name('attendee_join');

Route::post('/konn3ct', [RoomController::class, 'fjoin'])->name('konn3ct');

Route::get('/roomstatus/{url}', [RoomController::class, 'roomstatus'])->name('roomstatus');

Route::get('/preregistration', function () {
    abort(404);
});

Route::get('/preregistration/{url}', [RoomController::class, 'preregshow'])->name('preregshow');

Route::post('/registerprereg', [RoomController::class, 'registerprereg'])->name('registerprereg');

Route::get('/preregistrationsuccess', function () {
    return view('success');
})->name('preregsuccess');

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

    Route::post('/preregistration', [RoomController::class, 'prereg'])->name('prereg');

    Route::get('/preregistration_participants/{reference}', [RoomController::class, 'prereParticipants'])->name('prereParticipants');

    Route::get('/disbalepreregistration/{reference}', [RoomController::class, 'dprereg'])->name('dprereg');

    Route::get('/preregusers/{reference}', [RoomController::class, 'preregusers'])->name('preregusers');

    Route::get('/addonsubscription', [AddonController::class, 'show'])->name('addonsubscription');

    Route::get('/activateft', [PaymentController::class, 'activatefree'])->name('activatefree');

    Route::get('/payment', [PaymentController::class, 'list'])->name('payments');

    Route::get('/receipt', [PaymentController::class, 'receipt'])->name('receipt');

    Route::get('/exportreceipt', [PaymentController::class, 'exportreceipt'])->name('exportreceipt');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/user/profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/recording', [RecordingController::class, 'show'])->name('recording');

    Route::get('/referee', [ProfileController::class, 'referee'])->name('referee');

    Route::get('/attendance/{id}', [RoomController::class, 'attendance'])->name('attendance');

    Route::get('/participants/{id}', [RoomController::class, 'participants'])->name('participants');

    Route::post('/deleterecording', [RecordingController::class, 'delete'])->name('recording.delete');

    Route::post('/invite', [RoomController::class, 'invite'])->name('invite');

    Route::post('/whatsappinvite', [RoomController::class, 'invite_whatsapp'])->name('whatsappinvite');

    Route::get('/invites', [OtherController::class, 'invites'])->name('invites');

    Route::get('/resendinvites/{id}', [OtherController::class, 'resendinvite'])->name('resendinvites');

    Route::post('/accesscode', [RoomController::class, 'accesscode'])->name('accesscode');

    Route::post('/limituser', [RoomController::class, 'limituser'])->name('limituser');

    Route::post('/bannerupload', [RoomController::class, 'bannerupload'])->name('bannerupload');

    Route::get('/welcomemail', function () {
        return (new UserWelcomeMail())->render();
    })->name('mailtest');

Route::get('/invitemail', function (){
        $data['ihost']="Samji";

        $data['ilink']=url('/join/')."login";

        $data['idate']="2020-12";

        $data['iaccesscode']="hello";

        $data['itime']="12:40";

        $data['iroom']="Sammy Room";

    return (new UserWelcomeMail($data))->render();
    })->name('mailtest');

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/pay', function () {
        return view('payment');
    })->name('payment');

//    Route::get('/payment/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/payment/mastercard/{id}', [MasterCardGatewayController::class, 'CreateSessionO'])->name('CreateSession');
    Route::get('/payment/mastercardview', function () {
        return view('mastercard');
    })->name('CreateSession');

    Route::get('/payment/{plan}/transid/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/addonpayment/{plan}/transid/{id}', [PaymentController::class, 'verifyAddonsub'])->name('verifyAddonsub');

    Route::get('/paystackpayment/{plan}/transid/{id}', [PaymentController::class, 'verifyPaystack'])->name('verifypaystackpayment');

    Route::get('/changeplan/{plan}', [PaymentController::class, 'changeplan'])->name('changeplan');

    Route::get('/logouts', [AuthenticatedSessionController::class, 'destroy'])->name('logouts');

});

require __DIR__ . '/admin.php';
