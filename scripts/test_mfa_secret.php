<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;

$tfaProvider = app(TwoFactorAuthenticationProvider::class);
$google2fa = new Google2FA();

$user = User::where('email', 'admin_mfa@example.com')->first();

echo "User Details:\n";
echo "ID: " . $user->id . "\n";
echo "Email: " . $user->email . "\n";
echo "Type: " . $user->type . "\n";

$decrypted = decrypt($user->two_factor_secret);
echo "Two Factor Secret: " . $decrypted . "\n";

$currentOtp = $google2fa->getCurrentOtp($decrypted);
echo "Current TOTP Code (Time: " . date('Y-m-d H:i:s') . "): " . $currentOtp . "\n";

$isValid = $tfaProvider->verify($decrypted, $currentOtp);
echo "Verification result: " . ($isValid ? 'VALID' : 'INVALID') . "\n";
