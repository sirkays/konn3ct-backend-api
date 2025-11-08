<?php


use App\Http\Controllers\PaystackHookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('paystackhook', [PaystackHookController::class, 'index']);
Route::post('paystackhookweb', [PaystackHookController::class, 'webHook']);
Route::post('hook/meeting', [\App\Http\Controllers\WebhookController::class, 'meeting']);
Route::post('hook/vulte', [\App\Http\Controllers\VulteHookController::class, 'index']);
Route::post('hook/polaris', [\App\Http\Controllers\VulteHookController::class, 'bankTransfer']);
