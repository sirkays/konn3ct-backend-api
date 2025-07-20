<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MyAuthController;
use App\Http\Controllers\MyCustomWebSocketHandler;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Payments\MasterCardGatewayController;
use App\Http\Controllers\Payments\PaystackPayment;
use App\Http\Controllers\PreregistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StreamingController;
use App\Mail\WelcomeMailViaJoin;
use App\Models\Faq;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
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

Route::get('/userjoin/{params}', function ($params) {
    return redirect()->away(env('BBB_SERVER_BASE_URL') . 'api/join?' . decrypt($params));
});

Route::get('/offline', function () {
    return view('vendor/laravelpwa/offline');
});

Route::get('/', function () {
    \LaravelFacebookPixel::createEvent('Home Page Visit', $parameters = []);
    return view('welcome');
})->name('welcome');

Route::get('/register/{id}', [MyAuthController::class, 'register']);

Route::get('/pricing', function () {
    \LaravelFacebookPixel::createEvent('Pricing', $parameters = []);
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

Route::get('/join/{url}', [RoomController::class, 'urljoin']);

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

Route::middleware(['auth:sanctum', 'verified', 'NewUserPlanCheck', 'checksub'])->group(function () {

    Route::post('/addReferral', [OtherController::class, 'addReferral'])->name('addReferral');

    Route::post('/createroom', [RoomController::class, 'create'])->name('create_room');

    Route::post('/joinroom', [RoomController::class, 'mjoin'])->name('moderator_join');

    Route::post('/deleteroom', [RoomController::class, 'delete'])->name('delete');

    Route::get('/myrooms', [RoomController::class, 'show'])->name('rooms');

    Route::get('/dashboard', [RoomController::class, 'show'])->name('dashboard');

    Route::post('/preregistration', [PreregistrationController::class, 'prereg'])->name('prereg');

    Route::post('/start-streaming', [StreamingController::class, 'startStreaming'])->name('startStreaming');
    Route::get('/stop-streaming/{id}', [StreamingController::class, 'stopStreaming'])->name('stopStreaming');
    Route::get('/streamings', [StreamingController::class, 'list'])->name('streamList');

    Route::post('/modify-preregistration', [PreregistrationController::class, 'preregModify'])->name('preregModify');

    Route::get('/preregistration_participants/{reference}', [PreregistrationController::class, 'prereParticipants'])->name('prereParticipants');

    Route::get('/sendPreregReminder/{reference}', [PreregistrationController::class, 'sendReminder'])->name('preregSendReminder');

    Route::get('/disbalepreregistration/{reference}', [PreregistrationController::class, 'dprereg'])->name('dprereg');

    Route::get('/addonsubscription', [AddonController::class, 'show'])->name('addonsubscription');

    Route::get('/activateft', [PaymentController::class, 'activatefree'])->name('activatefree');

    Route::get('/payment', [PaymentController::class, 'list'])->name('payments');

    Route::get('/paymentreceipt', [PaymentController::class, 'receipt'])->name('paymentreceipt');

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

    Route::post('/transfer-room', [RoomController::class, 'transferRoom'])->name('transferRoom');

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
        \LaravelFacebookPixel::createEvent('Payment Page Visit', $parameters = []);
        return view('payment');
    })->name('payment');

    Route::get('/payment/stripe/{id}', [\App\Http\Controllers\Payments\StripePayment::class, 'process'])->name('payment_stripe');
    Route::get('/payment/verify/stripe', [\App\Http\Controllers\Payments\StripePayment::class, 'verify'])->name('payment_verify_stripe');

    Route::get('/payment/paystack/{id}', [PaystackPayment::class, 'process'])->name('payment_paystack');
    Route::get('/payment/verify/paystack/{reference}', [PaystackPayment::class, 'verify'])->name('payment_verify_paystack');

    Route::get('/payment/mastercard-view/{id}', [MasterCardGatewayController::class, 'launchView'])->name('payment_mastercard');
    Route::post('/payment/mastercard', [MasterCardGatewayController::class, 'makePayment'])->name('makePayment.Mastercard');
    Route::get('/payment/mastercardstatus', [MasterCardGatewayController::class, 'paymentStatus'])->name('mastercard.status');
    Route::get('/payment/mastercard/{id}', [MasterCardGatewayController::class, 'makePayment'])->name('makePayment');


    Route::get('/payment/{id}', [PaymentController::class, 'verify'])->name('verifypayment');


    Route::post('/apply-coupon', [\App\Http\Controllers\admin\CouponController::class, 'apply'])->name('apply.coupon');


    Route::get('/payment/{plan}/transid/{id}', [PaymentController::class, 'verify'])->name('verifypayment');

    Route::get('/addonpayment/{plan}/transid/{id}', [PaymentController::class, 'verifyAddonsub'])->name('verifyAddonsub');

    Route::get('/paystackpayment/{plan}/transid/{id}', [PaymentController::class, 'verifyPaystack'])->name('verifypaystackpayment');

    Route::get('/changeplan/{plan}', [PaymentController::class, 'changeplan'])->name('changeplan');

    Route::get('/logouts', [AuthenticatedSessionController::class, 'destroy'])->name('logouts');
});

WebSocketsRouter::webSocket('/pws', MyCustomWebSocketHandler::class);
WebSocketsRouter::webSocket('/pws/{appKey}', MyCustomWebSocketHandler::class);
WebSocketsRouter::webSocket('/my-websocket', MyCustomWebSocketHandler::class);
WebSocketsRouter::webSocket('/my-websocket/{appKey}', MyCustomWebSocketHandler::class);

require __DIR__ . '/admin.php';
require __DIR__ . '/storage.php';
require __DIR__ . '/test.php';

Route::get('{any?}', function () {
    return redirect()->away('https://dashboard.konn3ct.ng/');
})->where('any', '.*');

