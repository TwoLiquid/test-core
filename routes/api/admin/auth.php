<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Admin Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {

    /**
     * API authentication routes
     */
    Route::group([
        'namespace' => 'Admin',
        'prefix'    => 'admin/auth'
    ], function () {

        /**
         * Admin auth namespace
         */
        Route::group(['namespace' => 'Auth'], function () {

            /**
             * Providing admin authentication
             */
            Route::post('login', 'AuthController@login')
                ->name('api.admin.auth.login');

            /**
             * Providing getting two factor
             */
            Route::post('two/factor', 'AuthController@getTwoFactor')
                ->name('api.admin.auth.two.factor');

            /**
             * Providing two factor enabling
             */
            Route::post('two/factor/enable', 'AuthController@enableTwoFactor')
                ->name('api.admin.auth.two.factor.enable');

            /**
             * Providing two factor executing
             */
            Route::post('two/factor/execute', 'AuthController@executeTwoFactor')
                ->name('api.admin.auth.two.factor.execute');

            /**
             * Providing password reset
             */
            Route::post('password/reset', 'AuthController@passwordReset')
                ->name('api.admin.auth.password.reset');

            /**
             * Providing password setup
             */
            Route::post('password/setup', 'AuthController@passwordSetup')
                ->name('api.admin.auth.password.setup');

            Route::group([
                'middleware' => ['gateway', 'auth.admin']
            ], function () {

                /**
                 * Providing getting admin
                 */
                Route::get('admin', 'AuthController@getAuthAdmin')
                    ->name('api.admin.auth.admin');

                /**
                 * Providing admin logout
                 */
                Route::post('logout', 'AuthController@logout')
                    ->name('api.admin.auth.logout');
            });
        });
    });
});