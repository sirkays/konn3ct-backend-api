<?php

use App\Mail\UserWelcomeMail;
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

//Route::get('/aa', [PreregistrationController::class, 'checkReminder']);

Route::get('/teststripe', [\App\Http\Controllers\Payments\StripePayment::class, 'process']);
Route::get('/testpaystack', [\App\Http\Controllers\Payments\PaystackPayment::class, 'process']);

Route::get('/trigger/{data}', function ($data) {
    echo "<p>You have sent $data.</p>";
    \App\Events\HealthEvent::dispatch($data);
});

Route::get('/pmailable', function () {
    $jobi['days'] = 14;
    $jobi['user'] = \App\Models\User::find(1);

    return new \App\Mail\SubscriptionReminderMail($jobi);
});

Route::get('/welcomemail', function () {
    return (new UserWelcomeMail())->render();
})->name('mailtest');


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


