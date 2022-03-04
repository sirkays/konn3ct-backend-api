<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MasterCardGatewayController;
use App\Http\Controllers\MyAuthController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PreregistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\RoomController;
use App\Mail\UserWelcomeMail;
use App\Mail\WelcomeMailViaJoin;
use App\Models\Faq;
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

//Route::get('/aa', [PreregistrationController::class, 'checkReminder']);

Route::get('/pmailable', function () {
    $jobi['days'] = 14;
    $jobi['user'] = \App\Models\User::find(1);

    return new \App\Mail\SubscriptionReminderMail($jobi);
});

Route::get('/welcomemail', function () {
    return (new UserWelcomeMail())->render();
})->name('mailtest');


Route::get('/userjoin/{params}', function ($params) {
    return redirect()->away('https://konn3ct.com/bigbluebutton/api/join?' . decrypt($params));
});

Route::get('/nsu', function () {
    return view('new-signup');
})->name('new-signup');

Route::get('/njm', function () {
    return view('new-joinmeeting');
})->name('new-joinmeeting');

Route::get('/nrs', function () {
    return view('new-roompreview');
});

Route::get('/nrpa', function () {
    return view('new-roompreviewavailable');
});

Route::get('/nps', function () {
    return view('new-pricing');
});

Route::get('/ncs', function () {
    return view('new-contactsales');
})->name("contactsales");

Route::get('/nhp', function () {
    return view('new-homepage');
})->name('new-homepage');


Route::get('/offline', function () {
    return view('vendor/laravelpwa/offline');
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register/{id}', [MyAuthController::class, 'register']);

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/joinsession', function () {
    return view('join_session');
})->name('joinmeeting');

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

Route::get('/preregistration/{url}', [PreregistrationController::class, 'preregshow'])->name('preregshow');

Route::post('/registerprereg', [PreregistrationController::class, 'registerprereg'])->name('registerprereg');

Route::get('/preregistrationsuccess', function () {
    $data['faqs'] = Faq::where('status', 1)->get();
    return view('success', $data);
})->name('preregsuccess');

Route::get('/features', function () {
    return view('features');
});

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'index'])->name('contactsent');

Route::get('/myroombanner/{filename}', function ($filename) {
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

Route::get('/prereg/{filename}', function ($filename)
{
    $path = storage_path('prereg/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->name('show.prereg.image');



Route::middleware(['auth:sanctum', 'verified', 'NewUserPlanCheck', 'checksub'])->group(function () {

    Route::post('/addReferral', [OtherController::class, 'addReferral'])->name('addReferral');

    Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');

    Route::post('/joinroom', [RoomController::class, 'mjoin'])->name('moderator_join');

    Route::post('/deleteroom', [RoomController::class, 'delete'])->name('delete');

    Route::get('/myrooms', [RoomController::class, 'show'])->name('rooms');

    Route::get('/dashboard', [RoomController::class, 'show'])->name('dashboard');

    Route::post('/preregistration', [PreregistrationController::class, 'prereg'])->name('prereg');

    Route::get('/preregistration_participants/{reference}', [PreregistrationController::class, 'prereParticipants'])->name('prereParticipants');

    Route::get('/disbalepreregistration/{reference}', [PreregistrationController::class, 'dprereg'])->name('dprereg');

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

    Route::post('/invite', [InviteController::class, 'invite'])->name('invite');

    Route::post('/whatsappinvite', [InviteController::class, 'invite_whatsapp'])->name('whatsappinvite');

    Route::get('/invites', [InviteController::class, 'invites'])->name('invites');

    Route::get('/resendinvites/{id}', [InviteController::class, 'resendinvite'])->name('resendinvites');

    Route::post('/accesscode', [RoomController::class, 'accesscode'])->name('accesscode');

    Route::post('/limituser', [RoomController::class, 'limituser'])->name('limituser');

    Route::post('/bannerupload', [RoomController::class, 'bannerupload'])->name('bannerupload');

    Route::get('/apitokens', function () {
        return view('user.api');
    })->name('apitokens');


    Route::get('/invitemail', function () {
        $data['ihost'] = "Samji";

        $data['ilink'] = url('/join/') . "login";

        $data['idate'] = "2020-12";

        $data['iaccesscode'] = "hello";

    $data['itime'] = "12:40";

    $data['iroom'] = "Sammy Room";
    $data['email'] = "Sammy Room";
    $data['password'] = "passwi";

    return (new WelcomeMailViaJoin($data))->render();
})->name('mailtest');

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/pay', function () {
        return view('payment');
    })->name('payment');

//    Route::get('/payment/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/payment/mastercard/{id}', [MasterCardGatewayController::class, 'makePayment'])->name('makePayment');

    Route::get('/payment/mastercard/{plan}/{type}', [MasterCardGatewayController::class, 'launchView'])->name('mastercard_payment');

    Route::post('/payment/mastercard', [MasterCardGatewayController::class, 'makePayment'])->name('makePayment.Mastercard');

    Route::get('/payment/mastercardstatus', [MasterCardGatewayController::class, 'paymentStatus'])->name('mastercard.status');


    Route::post('/apply-coupon', [\App\Http\Controllers\admin\CouponController::class, 'apply'])->name('apply.coupon');


    Route::get('/payment/{plan}/transid/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/addonpayment/{plan}/transid/{id}', [PaymentController::class, 'verifyAddonsub'])->name('verifyAddonsub');

    Route::get('/paystackpayment/{plan}/transid/{id}', [PaymentController::class, 'verifyPaystack'])->name('verifypaystackpayment');

    Route::get('/changeplan/{plan}', [PaymentController::class, 'changeplan'])->name('changeplan');

    Route::get('/logouts', [AuthenticatedSessionController::class, 'destroy'])->name('logouts');

});

require __DIR__ . '/admin.php';
