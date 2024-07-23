<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Guest Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {

    /**
     * Auth namespace
     */
    Route::group([
        'namespace' => 'Guest\Auth',
        'prefix'    => 'auth'
    ], function () {

        /**
         * Getting user registration form
         */
        Route::get('register/form', 'AuthController@getRegisterForm')
            ->name('api.auth.register.form');

        /**
         * Providing checking user name is already registered
         */
        Route::post('check/username', 'AuthController@checkUsername')
            ->name('api.auth.check.username');

        /**
         * Providing checking user email is already registered
         */
        Route::post('check/email', 'AuthController@checkEmail')
            ->name('api.auth.check.email');

        /**
         * Providing user registration
         */
        Route::post('register', 'AuthController@register')
            ->name('api.auth.register');

        /**
         * Providing user authentication
         */
        Route::post('login', 'AuthController@login')
            ->name('api.auth.login');

        /**
         * Providing register email verify
         */
        Route::get('register/email/verify', 'AuthController@registerEmailVerify')
            ->name('api.auth.register.email.verify');

        /**
         * Providing password reset initialize
         */
        Route::post('password/reset/initialize', 'AuthController@initializePasswordReset')
            ->name('api.auth.password.reset.initialize');

        /**
         * Providing password reset initialize
         */
        Route::post('password/reset', 'AuthController@resetPassword')
            ->name('api.auth.password.reset');
    });
});
