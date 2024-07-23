<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API General Auth Routes
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
        'namespace'  => 'General\Auth',
        'middleware' => ['gateway', 'auth.user', 'localization'],
        'prefix'     => 'auth'
    ], function () {

        /**
         * Getting auth user
         */
        Route::get('user', 'AuthController@getAuthUser')
            ->name('api.auth.user');

        /**
         * Attaching auth user subscription
         */
        Route::post('user/subscription/{subscriptionId}/attach', 'AuthController@attachSubscription')
            ->name('api.user.subscription.attach');

        /**
         * Detaching auth user subscription
         */
        Route::post('user/subscription/{subscriptionId}/detach', 'AuthController@detachSubscription')
            ->name('api.user.subscription.detach');

        /**
         * Attaching auth user favorite vybe
         */
        Route::post('user/favorite/vybe/{id}/attach', 'AuthController@attachFavoriteVybe')
            ->name('api.user.favorite.vybe.attach');

        /**
         * Detaching auth user favorite vybe
         */
        Route::post('user/favorite/vybe/{id}/detach', 'AuthController@detachFavoriteVybe')
            ->name('api.user.favorite.vybe.detach');

        /**
         * Attaching auth user favorite activity
         */
        Route::post('user/favorite/activity/{id}/attach', 'AuthController@attachFavoriteActivity')
            ->name('api.user.favorite.activity.attach');

        /**
         * Attaching auth user favorite activities
         */
        Route::post('user/favorite/activities/attach', 'AuthController@attachFavoriteActivities')
            ->name('api.user.favorite.activities.attach');

        /**
         * Detaching auth user favorite activity
         */
        Route::post('user/favorite/activity/{id}/detach', 'AuthController@detachFavoriteActivity')
            ->name('api.user.favorite.activity.detach');

        /**
         * Detaching auth user favorite activities
         */
        Route::post('user/favorite/activities/detach', 'AuthController@detachFavoriteActivities')
            ->name('api.user.favorite.activities.detach');

        /**
         * Attaching auth blocked user
         */
        Route::post('user/blocked/user/{blockedUserId}/attach', 'AuthController@attachBlockedUser')
            ->name('api.user.blocked.user.attach');

        /**
         * Detaching auth blocked user
         */
        Route::post('user/blocked/user/{blockedUserId}/detach', 'AuthController@detachBlockedUser')
            ->name('api.user.blocked.user.detach');

        /**
         * Providing auth user logout
         */
        Route::post('logout', 'AuthController@logout')
            ->name('api.auth.logout');
    });
});
