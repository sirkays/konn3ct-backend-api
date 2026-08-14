<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

$user = User::where('email', 'admin_mfa@example.com')->first();
$decryptedSecret = decrypt($user->two_factor_secret);

$google2fa = new Google2FA();
$currentOtp = $google2fa->getCurrentOtp($decryptedSecret);

echo "1. Testing Login without MFA code (Should return 202 MFA_REQUIRED)...\n";
$request1 = Request::create('/api/v1/admin/auth/login', 'POST', [
    'email' => 'admin_mfa@example.com',
    'password' => 'password',
]);
$response1 = $kernel->handle($request1);
echo "Status Code: " . $response1->getStatusCode() . "\n";
echo "Response Body: " . $response1->getContent() . "\n\n";

echo "2. Testing Login with valid MFA code ({$currentOtp})...\n";
$request2 = Request::create('/api/v1/admin/auth/login', 'POST', [
    'email' => 'admin_mfa@example.com',
    'password' => 'password',
    'mfa_code' => $currentOtp,
]);
$response2 = $kernel->handle($request2);
echo "Status Code: " . $response2->getStatusCode() . "\n";
echo "Response Body: " . $response2->getContent() . "\n";
