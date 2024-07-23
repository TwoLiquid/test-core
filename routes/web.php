<?php

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

/**
 * Index route
 */
Route::get('/', 'MainController@index')->name('index');

/**
 * Register email verify
 */
Route::get('register/email/verify', 'Auth\VerificationController@registerEmailVerify')->name('register.email.verify');

/**
 * Test payment
 */
Route::get('test/payment', 'MainController@testPayment')->name('test.payment');
Route::get('test/payment/cancel', 'MainController@testPaymentCancel')->name('test.payment.cancel');