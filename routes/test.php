<?php

use App\Mail\UserWelcomeMail;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
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


Route::get('/nhp0989', function () {
    $bbb = new BigBlueButton();

    $createMeetingParams = new CreateMeetingParameters("teslt9", "hello0");
//    $createMeetingParams->setParentMeetingId("hfh88fh");
    $createMeetingResponse = $bbb->createMeeting($createMeetingParams);

    echo $createMeetingResponse->getRawXml();

    if ($createMeetingResponse->success()) {
        echo "success";
    } else {
        echo "failure";
    }
});


Route::get('/nhp0989j', function () {
    $url = \Bigbluebutton::join([
        'meetingID' => 'tamku',
        'userName' => 'disa',
        'password' => 'attendee', //which user role want to join set password here
        'redirect' => false, //it will not redirect into bigblueserver
        'userId' => "54575",
        'customParameters' => [
            'foo' => 'bar',
            'key' => 'value'
        ]
    ]);

    return $url;
})->name('new-homepagew');


