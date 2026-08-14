<?php


use App\Http\Controllers\PaystackHookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Hook Routes
|--------------------------------------------------------------------------
| Webhook routes for payment providers. All routes in this file are
| exempt from CSRF (applied in VerifyCsrfToken::$except).
|
| SECURITY NOTE — Vulte routes:
|   The vulte.ip middleware applies an IP allowlist as interim mitigation.
|   Vulte does not provide a published HMAC/signature contract. See:
|   config/vulte.php and app/Http/Middleware/VulteIpAllowlist.php
*/

// Paystack webhooks: raw body preserved before JSON parsing for HMAC-SHA512 verification.
Route::post('paystackhook',    [PaystackHookController::class, 'index'])  ->middleware('raw.body');
Route::post('paystackhookweb', [PaystackHookController::class, 'webHook'])->middleware('raw.body');

Route::post('hook/meeting', [\App\Http\Controllers\WebhookController::class, 'meeting']);

// Vulte webhooks: IP allowlist applied. Live flow preserved — do NOT add signature
// verification without confirming the contract with Vulte support.
Route::post('hook/vulte',   [\App\Http\Controllers\VulteHookController::class, 'index'])       ->middleware('vulte.ip');
Route::post('hook/polaris', [\App\Http\Controllers\VulteHookController::class, 'bankTransfer'])->middleware('vulte.ip');
