<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

$email = $argv[1] ?? 'admin_mfa@example.com';
$user = User::where('email', $email)->first();

if (!$user || !$user->two_factor_secret) {
    echo "Error: User {$email} not found or has no MFA secret configured.\n";
    exit(1);
}

$secret = decrypt($user->two_factor_secret);
$google2fa = new Google2FA();
$otp = $google2fa->getCurrentOtp($secret);

echo "User: " . $user->email . "\n";
echo "Secret Key: " . $secret . "\n";
echo "Current TOTP Code: " . $otp . "\n";
