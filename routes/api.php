<?php

use App\Http\Controllers\PaypalController;
use Illuminate\Http\Request;
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

Route::get('/paypal/checkout', [PaypalController::class, 'checkout'])->name('payment.checkout');
Route::get('/paypal/payment/done', [PaypalController::class, 'getDone'])->name('payment.done');
Route::get('/paypal/payment/cancel', [PaypalController::class, 'getCancel'])->name('payment.cancel');
