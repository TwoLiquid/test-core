<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {

    /**
     * Admin API routes
     */
    Route::group([
        'namespace'  => 'Admin',
        'middleware' => ['gateway', 'auth.admin'],
        'prefix'     => 'admin'
    ], function () {

        /**
         * Csau namespace
         */
        Route::group(['namespace' => 'Csau', 'prefix' => 'csau'], function () {

            /**
             * Category namespace
             */
            Route::group(['namespace' => 'Category'], function () {

                /**
                 * CSAU categories CRUD
                 */
                Route::resource('categories', 'CategoryController')
                    ->parameters([
                        'categories' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.categories.index',
                        'show'    => 'api.admin.csau.categories.show',
                        'store'   => 'api.admin.csau.categories.store',
                        'update'  => 'api.admin.csau.categories.update',
                        'destroy' => 'api.admin.csau.categories.destroy'
                    ]);

                /**
                 * Provides categories positions update
                 */
                Route::patch('categories/update/positions', 'CategoryController@updatePositions')
                    ->name('api.admin.csau.categories.update.positions');

                /**
                 * CSAU subcategories CRUD
                 */
                Route::resource('categories/subcategories', 'SubcategoryController')
                    ->parameters([
                        'subcategories' => 'id'
                    ])
                    ->except([
                        'index',
                        'show',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'store' => 'api.admin.csau.categories.subcategories.store'
                    ]);

                /**
                 * CSAU activities CRUD
                 */
                Route::resource('categories/activities', 'ActivityController')
                    ->parameters([
                        'activities' => 'id'
                    ])
                    ->except([
                        'index',
                        'show',
                        'create',
                        'edit'
                    ])
                    ->names([
                        'store'   => 'api.admin.csau.categories.activities.store',
                        'update'  => 'api.admin.csau.categories.activities.update',
                        'destroy' => 'api.admin.csau.categories.activities.destroy'
                    ]);

                /**
                 * Provides activities positions update
                 */
                Route::patch('categories/activities/update/positions', 'ActivityController@updatePositions')
                    ->name('api.admin.csau.categories.activities.update.positions');

                /**
                 * Provides attaching activity tags to activities
                 */
                Route::post('categories/activities/activity/tags/attach', 'ActivityController@attachActivityTags')
                    ->name('api.admin.csau.categories.activities.activity.tags.attach');

                /**
                 * Provides detaching activity tag from activities
                 */
                Route::post('categories/activities/{id}/activity/tag/{activityTagId}/detach', 'ActivityController@detachActivityTag')
                    ->name('api.admin.csau.categories.activities.activity.tag.detach');

                /**
                 * Provides attaching unit to activity
                 */
                Route::post('categories/activities/{id}/units/attach', 'ActivityController@attachUnits')
                    ->name('api.admin.csau.categories.activities.units.attach');

                /**
                 * Provides detaching unit from activity
                 */
                Route::post('categories/activities/{id}/unit/{unitId}/detach', 'ActivityController@detachUnit')
                    ->name('api.admin.csau.categories.activities.unit.detach');

                /**
                 * Provides attaching devices to activity
                 */
                Route::post('categories/activities/{id}/devices/attach', 'ActivityController@attachDevices')
                    ->name('api.admin.csau.categories.activities.devices.attach');

                /**
                 * Provides detaching device from activity
                 */
                Route::post('categories/activities/{id}/device/{deviceId}/detach', 'ActivityController@detachDevice')
                    ->name('api.admin.csau.categories.activities.device.detach');

                /**
                 * Provides attaching platforms to activity
                 */
                Route::post('categories/activities/{id}/platforms/attach', 'ActivityController@attachPlatforms')
                    ->name('api.admin.csau.categories.activities.platforms.attach');

                /**
                 * Provides detaching platform from activity
                 */
                Route::post('categories/activities/{id}/platform/{platformId}/detach', 'ActivityController@detachPlatform')
                    ->name('api.admin.csau.categories.activities.platform.detach');

                /**
                 * CSAU units CRUD
                 */
                Route::resource('categories/units', 'UnitController')
                    ->parameters([
                        'units' => 'id'
                    ])
                    ->except([
                        'index',
                        'show',
                        'create',
                        'edit'
                    ])
                    ->names([
                        'store'   => 'api.admin.csau.categories.units.store',
                        'update'  => 'api.admin.csau.categories.units.update',
                        'destroy' => 'api.admin.csau.categories.units.destroy'
                    ]);

                /**
                 * Provides units positions update
                 */
                Route::patch('categories/units/update/positions', 'UnitController@updatePositions')
                    ->name('api.admin.csau.categories.units.update.positions');
            });

            /**
             * Activity tag namespace
             */
            Route::group(['namespace' => 'ActivityTag'], function () {

                /**
                 * Activity tags CRUD
                 */
                Route::resource('activity/tags', 'ActivityTagController')
                    ->parameters([
                        'tags' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.activity.tags.index',
                        'show'    => 'api.admin.csau.activity.tags.show',
                        'store'   => 'api.admin.csau.activity.tags.store',
                        'update'  => 'api.admin.csau.activity.tags.update',
                        'destroy' => 'api.admin.csau.activity.tags.destroy'
                    ]);

                /**
                 * Provides attaching activities to activity tag
                 */
                Route::post('activity/tags/{id}/activities/attach', 'ActivityTagController@attachActivities')
                    ->name('api.admin.csau.activity.tags.activities.attach');

                /**
                 * Provides detaching activity to activity tag
                 */
                Route::post('activity/tags/{id}/activity/{activityId}/detach', 'ActivityTagController@detachActivity')
                    ->name('api.admin.csau.activity.tags.activity.detach');
            });

            /**
             * Device namespace
             */
            Route::group(['namespace' => 'Device'], function () {

                /**
                 * Devices CRUD
                 */
                Route::resource('devices', 'DeviceController')
                    ->parameters([
                        'devices' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.devices.index',
                        'show'    => 'api.admin.csau.devices.show',
                        'store'   => 'api.admin.csau.devices.store',
                        'update'  => 'api.admin.csau.devices.update',
                        'destroy' => 'api.admin.csau.devices.destroy'
                    ]);

                /**
                 * Provides getting device vybes
                 */
                Route::get('devices/{id}/vybes', 'DeviceController@getVybes')
                    ->name('api.admin.csau.devices.vybes');

                /**
                 * Provides getting device activities
                 */
                Route::get('devices/{id}/activities', 'DeviceController@getActivities')
                    ->name('api.admin.csau.devices.activities');

                /**
                 * Provides attaching activities to device
                 */
                Route::post('devices/{id}/activities/attach', 'DeviceController@attachActivities')
                    ->name('api.admin.csau.devices.activities.attach');

                /**
                 * Provides detaching activity from device
                 */
                Route::post('devices/{id}/activity/{activityId}/detach', 'DeviceController@detachActivity')
                    ->name('api.admin.csau.devices.activity.detach');
            });

            /**
             * Platform namespace
             */
            Route::group(['namespace' => 'Platform'], function () {

                /**
                 * Platforms CRUD
                 */
                Route::resource('platforms', 'PlatformController')
                    ->parameters([
                        'platforms' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.platforms.index',
                        'show'    => 'api.admin.csau.platforms.show',
                        'store'   => 'api.admin.csau.platforms.store',
                        'update'  => 'api.admin.csau.platforms.update',
                        'destroy' => 'api.admin.csau.platforms.destroy'
                    ]);

                /**
                 * Provides getting platform vybes
                 */
                Route::get('platforms/{id}/vybes', 'PlatformController@getVybes')
                    ->name('api.admin.csau.platforms.vybes');

                /**
                 * Provides getting platform activities
                 */
                Route::get('platforms/{id}/activities', 'PlatformController@getActivities')
                    ->name('api.admin.csau.platforms.activities');

                /**
                 * Provides attaching activities to platform
                 */
                Route::post('platforms/{id}/activities/attach', 'PlatformController@attachActivities')
                    ->name('api.admin.csau.platforms.activities.attach');

                /**
                 * Provides detaching activity from platform
                 */
                Route::post('platforms/{id}/activity/{activityId}/detach', 'PlatformController@detachActivity')
                    ->name('api.admin.csau.platforms.activity.detach');
            });

            /**
             * Suggestion namespace
             */
            Route::group(['namespace' => 'Suggestion', 'prefix' => 'suggestions'], function () {

                /**
                 * CSAU suggestions CRUD
                 */
                Route::resource('categories', 'CategoryController')
                    ->parameters([
                        'categories' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.suggestion.categories.index',
                        'show'    => 'api.admin.csau.suggestion.categories.show',
                        'store'   => 'api.admin.csau.suggestion.categories.store',
                        'update'  => 'api.admin.csau.suggestion.categories.update',
                        'destroy' => 'api.admin.csau.suggestion.categories.destroy'
                    ]);

                /**
                 * Device suggestions CRUD
                 */
                Route::resource('devices', 'DeviceController')
                    ->parameters([
                        'devices' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.suggestion.devices.index',
                        'show'    => 'api.admin.csau.suggestion.devices.show',
                        'store'   => 'api.admin.csau.suggestion.devices.store',
                        'update'  => 'api.admin.csau.suggestion.devices.update',
                        'destroy' => 'api.admin.csau.suggestion.devices.destroy'
                    ]);
            });

            /**
             * Unit namespace
             */
            Route::group(['namespace' => 'Unit'], function () {

                /**
                 * Units CRUD
                 */
                Route::resource('units', 'UnitController')
                    ->parameters([
                        'units' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.units.index',
                        'show'    => 'api.admin.csau.units.show',
                        'store'   => 'api.admin.csau.units.store',
                        'update'  => 'api.admin.csau.units.update',
                        'destroy' => 'api.admin.csau.units.destroy'
                    ]);

                /**
                 * Provides getting unit vybes
                 */
                Route::get('units/{id}/vybes', 'UnitController@getVybes')
                    ->name('api.admin.csau.units.vybes');

                /**
                 * Event units CRUD
                 */
                Route::resource('event/units', 'EventUnitController')
                    ->parameters([
                        'units' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.csau.event.units.index',
                        'show'    => 'api.admin.csau.event.units.show',
                        'store'   => 'api.admin.csau.event.units.store',
                        'update'  => 'api.admin.csau.event.units.update',
                        'destroy' => 'api.admin.csau.event.units.destroy'
                    ]);

                /**
                 * Provides getting event unit vybes
                 */
                Route::get('event/units/{id}/vybes', 'EventUnitController@getVybes')
                    ->name('api.admin.csau.event.units.vybes');
            });
        });

        /**
         * General namespace
         */
        Route::group(['namespace' => 'General'], function () {

            /**
             * Admin namespace
             */
            Route::group(['namespace' => 'Admin'], function () {

                /**
                 * Admin CRUD
                 */
                Route::resource('general/admins', 'AdminController')
                    ->parameters([
                        'admins' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.general.admins.index',
                        'show'    => 'api.admin.general.admins.show',
                        'store'   => 'api.admin.general.admins.store',
                        'update'  => 'api.admin.general.admins.update',
                        'destroy' => 'api.admin.general.admins.destroy'
                    ]);

                /**
                 * Getting form data
                 */
                Route::get('general/admins/form/data', 'AdminController@getForm')
                    ->name('api.admin.general.admins.form.data');

                /**
                 * Resetting admin password
                 */
                Route::post('general/admins/{id}/password/reset', 'AdminController@resetPassword')
                    ->name('api.admin.general.admins.password.reset');

                /**
                 * Deleting admin auth protection
                 */
                Route::delete('general/admins/{id}/two/factor', 'AdminController@unlinkTwoFactor')
                    ->name('api.admin.general.admins.two.factor');

                /**
                 * Roles CRUD
                 */
                Route::resource('general/admin/roles', 'RoleController')
                    ->parameters([
                        'roles' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.general.admin.roles.index',
                        'show'    => 'api.admin.general.admin.roles.show',
                        'store'   => 'api.admin.general.admin.roles.store',
                        'update'  => 'api.admin.general.admin.roles.update',
                        'destroy' => 'api.admin.general.admin.roles.destroy'
                    ]);

                /**
                 * Getting role form data
                 */
                Route::get('general/admin/roles/form/data', 'RoleController@getForm')
                    ->name('api.admin.general.admin.roles.form.data');
            });

            /**
             * Ip ban list namespace
             */
            Route::group(['namespace' => 'IpBanList'], function () {

                /**
                 * Ip ban list CRUD
                 */
                Route::resource('ip/ban/list', 'IpBanListController')
                    ->parameters([
                        'list' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit',
                        'update'
                    ])
                    ->names([
                        'index'   => 'api.ip.ban.list.index',
                        'show'    => 'api.ip.ban.list.show',
                        'store'   => 'api.ip.ban.list.store',
                        'destroy' => 'api.ip.ban.list.destroy'
                    ]);

                /**
                 * Deleting many ip ban list addresses
                 */
                Route::post('ip/ban/list/destroy/many', 'IpBanListController@destroyMany')
                    ->name('api.ip.ban.list.destroy.many');
            });

            /**
             * Payment namespace
             */
            Route::group(['namespace' => 'Payment'], function () {

                /**
                 * Payment methods CRUD
                 */
                Route::resource('general/payment/methods', 'PaymentMethodController')
                    ->parameters([
                        'methods' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.general.payment.methods.index',
                        'show'    => 'api.admin.general.payment.methods.show',
                        'store'   => 'api.admin.general.payment.methods.store',
                        'update'  => 'api.admin.general.payment.methods.update',
                        'destroy' => 'api.admin.general.payment.methods.destroy'
                    ]);

                /**
                 * Getting a payment methods form
                 */
                Route::get('general/payment/methods/get/form', 'PaymentMethodController@getForm')
                    ->name('api.admin.general.payment.methods.get.form');
            });

            /**
             * Ip registration list namespace
             */
            Route::group(['namespace' => 'IpRegistrationList'], function () {

                /**
                 * Ip registration list CRUD
                 */
                Route::resource('ip/registration/list', 'IpRegistrationListController')
                    ->parameters([
                        'list' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.ip.registration.list.index'
                    ]);
            });

            /**
             * Setting namespace
             */
            Route::group(['namespace' => 'Setting'], function () {

                /**
                 * General settings CRUD
                 */
                Route::resource('general/settings/general', 'GeneralSettingController')
                    ->parameters([
                        'general' => 'id'
                    ])
                    ->except([
                        'show',
                        'edit',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.general.settings.general.index'
                    ]);

                /**
                 * Updating general settings
                 */
                Route::patch('general/settings/general', 'GeneralSettingController@update')
                    ->name('api.admin.general.settings.general.update');

                /**
                 * Request settings CRUD
                 */
                Route::resource('general/settings/request', 'RequestSettingController')
                    ->parameters([
                        'request' => 'id'
                    ])
                    ->except([
                        'show',
                        'edit',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.general.settings.request.index'
                    ]);

                /**
                 * Updating request settings
                 */
                Route::patch('general/settings/request', 'RequestSettingController@update')
                    ->name('api.admin.general.settings.request.update');

                /**
                 * User settings CRUD
                 */
                Route::resource('general/settings/user', 'UserSettingController')
                    ->parameters([
                        'user' => 'id'
                    ])
                    ->except([
                        'show',
                        'edit',
                        'create',
                        'edit',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.general.settings.user.index'
                    ]);

                /**
                 * Updating user settings
                 */
                Route::patch('general/settings/user', 'UserSettingController@update')
                    ->name('api.admin.general.settings.user.update');

                /**
                 * Vybe settings CRUD
                 */
                Route::resource('general/settings/vybe', 'VybeSettingController')
                    ->parameters([
                        'vybe' => 'id'
                    ])
                    ->except([
                        'show',
                        'edit',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.general.settings.vybe.index'
                    ]);

                /**
                 * Updating vybe settings
                 */
                Route::patch('general/settings/vybe', 'VybeSettingController@update')
                    ->name('api.admin.general.settings.vybe.update');
            });

            /**
             * Suspicious word namespace
             */
            Route::group(['namespace' => 'SuspiciousWord'], function () {

                /**
                 * Bad words CRUD
                 */
                Route::resource('general/suspicious/words/bad', 'BadWordController')
                    ->parameters([
                        'bad' => 'id'
                    ])
                    ->except([
                        'show',
                        'edit',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.general.suspicious.words.bad.index'
                    ]);

                /**
                 * Updating bad words
                 */
                Route::patch('general/suspicious/words/bad', 'BadWordController@update')
                    ->name('api.admin.general.suspicious.words.bad.update');

                /**
                 * Exporting bad words file
                 */
                Route::get('general/suspicious/words/bad/export', 'BadWordController@export')
                    ->name('api.admin.general.suspicious.words.bad.export');

                /**
                 * Offensive words CRUD
                 */
                Route::resource('general/suspicious/words/offensive', 'OffensiveWordController')
                    ->parameters([
                        'offensive' => 'id'
                    ])
                    ->except([
                        'show',
                        'edit',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.general.suspicious.words.offensive.index'
                    ]);

                /**
                 * Updating offensive words
                 */
                Route::patch('general/suspicious/words/offensive', 'OffensiveWordController@update')
                    ->name('api.admin.general.suspicious.words.offensive.update');

                /**
                 * Exporting offensive words file
                 */
                Route::get('general/suspicious/words/offensive/export', 'OffensiveWordController@export')
                    ->name('api.admin.general.suspicious.words.offensive.export');

                /**
                 * Direct payment words CRUD
                 */
                Route::resource('general/suspicious/words/direct/payment', 'DirectPaymentWordController')
                    ->parameters([
                        'payment' => 'id'
                    ])
                    ->except([
                        'show',
                        'edit',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.general.suspicious.words.direct.payment.index'
                    ]);

                /**
                 * Updating direct payment words
                 */
                Route::patch('general/suspicious/words/direct/payment', 'DirectPaymentWordController@update')
                    ->name('api.admin.general.suspicious.words.direct.payment.update');

                /**
                 * Exporting direct payment words file
                 */
                Route::get('general/suspicious/words/direct/payment/export', 'DirectPaymentWordController@export')
                    ->name('api.admin.general.suspicious.words.direct.payment.export');
            });

            /**
             * Tax rule country namespace
             */
            Route::group(['namespace' => 'TaxRule'], function () {

                /**
                 * Tax rule countries CRUD
                 */
                Route::resource('tax/rule/countries', 'TaxRuleCountryController')
                    ->parameters([
                        'countries' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.tax.rules.countries.index',
                        'show'    => 'api.admin.tax.rules.countries.show',
                        'store'   => 'api.admin.tax.rules.countries.store',
                        'update'  => 'api.admin.tax.rules.countries.update',
                        'destroy' => 'api.admin.tax.rules.countries.destroy'
                    ]);

                /**
                 * Tax rule regions CRUD
                 */
                Route::resource('tax/rule/regions', 'TaxRuleRegionController')
                    ->parameters([
                        'regions' => 'id'
                    ])
                    ->except([
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.tax.rules.regions.index',
                        'show'    => 'api.admin.tax.rules.regions.show',
                        'store'   => 'api.admin.tax.rules.regions.store',
                        'update'  => 'api.admin.tax.rules.regions.update',
                        'destroy' => 'api.admin.tax.rules.regions.destroy'
                    ]);
            });
        });

        /**
         * Invoice namespace
         */
        Route::group(['namespace' => 'Invoice'], function () {

            /**
             * Add funds receipts CRUD
             */
            Route::resource('add/funds/receipts', 'AddFundsReceiptController')
                ->parameters([
                    'receipts' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.add.funds.receipts.index',
                    'show'  => 'api.admin.add.funds.receipts.show'
                ]);

            /**
             * Adding payment transaction to add funds receipt
             */
            Route::post('add/funds/receipts/{id}/payment', 'AddFundsReceiptController@addPayment')
                ->name('api.add.funds.receipts.payment');

            /**
             * Exporting add funds receipts
             */
            Route::get('add/funds/receipts/export/{type}', 'AddFundsReceiptController@export')
                ->name('api.admin.add.funds.receipts.export');

            /**
             * Getting invoice for add funds receipt pdf page
             */
            Route::get('add/funds/receipts/{id}/pdf', 'AddFundsReceiptController@viewPdf')
                ->name('api.admin.add.funds.receipts.pdf');

            /**
             * Downloading invoice for add funds receipt pdf file
             */
            Route::get('add/funds/receipts/{id}/pdf/download', 'AddFundsReceiptController@downloadPdf')
                ->name('api.admin.add.funds.receipts.pdf.download');

            /**
             * Invoices for buyers CRUD
             */
            Route::resource('invoice/for/buyers', 'InvoiceBuyerController')
                ->parameters([
                    'buyers' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.invoice.for.buyers.index',
                    'show'  => 'api.admin.invoice.for.buyers.show'
                ]);

            /**
             * Exporting invoice for buyers
             */
            Route::get('invoice/for/buyers/export/{type}', 'InvoiceBuyerController@export')
                ->name('api.admin.invoice.for.buyers.export');

            /**
             * Getting invoice for buyers pdf page
             */
            Route::get('invoice/for/buyers/{id}/pdf', 'InvoiceBuyerController@viewPdf')
                ->name('api.admin.invoice.for.buyers.pdf');

            /**
             * Downloading invoice for buyers pdf file
             */
            Route::get('invoice/for/buyers/{id}/pdf/download', 'InvoiceBuyerController@downloadPdf')
                ->name('api.admin.invoice.for.buyers.pdf.download');

            /**
             * Invoices for sellers CRUD
             */
            Route::resource('invoice/for/sellers', 'InvoiceSellerController')
                ->parameters([
                    'sellers' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.invoice.for.sellers.index',
                    'show'  => 'api.admin.invoice.for.sellers.show'
                ]);

            /**
             * Exporting invoice for sellers
             */
            Route::get('invoice/for/sellers/export/{type}', 'InvoiceSellerController@export')
                ->name('api.admin.invoice.for.sellers.export');

            /**
             * Getting invoice for sellers pdf page
             */
            Route::get('invoice/for/sellers/{id}/pdf', 'InvoiceSellerController@viewPdf')
                ->name('api.admin.invoice.for.sellers.pdf');

            /**
             * Downloading invoice for sellers pdf file
             */
            Route::get('invoice/for/sellers/{id}/pdf/download', 'InvoiceSellerController@downloadPdf')
                ->name('api.admin.invoice.for.sellers.pdf.download');

            /**
             * Tip invoices for buyers CRUD
             */
            Route::resource('tip/invoice/for/buyers', 'TipInvoiceBuyerController')
                ->parameters([
                    'buyers' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.tip.invoice.for.buyers.index',
                    'show'  => 'api.admin.tip.invoice.for.buyers.show'
                ]);

            /**
             * Exporting tip invoice for buyers
             */
            Route::get('tip/invoice/for/buyers/export/{type}', 'TipInvoiceBuyerController@export')
                ->name('api.admin.tip.invoice.for.buyers.export');

            /**
             * Getting invoice for tip invoice for buyer pdf page
             */
            Route::get('tip/invoice/for/buyers/{id}/pdf', 'TipInvoiceBuyerController@viewPdf')
                ->name('api.admin.tip.invoice.for.buyers.pdf');

            /**
             * Downloading invoice for tip invoice for buyer pdf file
             */
            Route::get('tip/invoice/for/buyers/{id}/pdf/download', 'TipInvoiceBuyerController@downloadPdf')
                ->name('api.admin.tip.invoice.for.buyers.pdf.download');

            /**
             * Tip invoices for sellers CRUD
             */
            Route::resource('tip/invoice/for/sellers', 'TipInvoiceSellerController')
                ->parameters([
                    'sellers' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.tip.invoice.for.sellers.index',
                    'show'  => 'api.admin.tip.invoice.for.sellers.show'
                ]);

            /**
             * Exporting tip invoice for sellers
             */
            Route::get('tip/invoice/for/sellers/export/{type}', 'TipInvoiceSellerController@export')
                ->name('api.admin.tip.invoice.for.sellers.export');

            /**
             * Getting invoice for tip invoice for seller pdf page
             */
            Route::get('tip/invoice/for/sellers/{id}/pdf', 'TipInvoiceSellerController@viewPdf')
                ->name('api.admin.tip.invoice.for.sellers.pdf');

            /**
             * Downloading invoice for tip invoice for seller pdf file
             */
            Route::get('tip/invoice/for/sellers/{id}/pdf/download', 'TipInvoiceSellerController@downloadPdf')
                ->name('api.admin.tip.invoice.for.sellers.pdf.download');

            /**
             * Getting withdrawal request
             */
            Route::get('withdrawal/requests/{id}', 'WithdrawalRequestController@index')
                ->name('api.admin.withdrawal.requests.index');

            /**
             * Updating withdrawal request
             */
            Route::patch('withdrawal/requests/{id}', 'WithdrawalRequestController@update')
                ->name('api.admin.withdrawal.requests.update');

            /**
             * Resending withdrawal request email
             */
            Route::post('withdrawal/requests/{id}/resend/email', 'WithdrawalRequestController@resendEmail')
                ->name('api.admin.withdrawal.requests.resend.email');

            /**
             * Withdrawal receipt CRUD
             */
            Route::resource('withdrawal/receipts', 'WithdrawalReceiptController')
                ->parameters([
                    'receipts' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.withdrawal.receipts.index',
                    'show'  => 'api.admin.withdrawal.receipts.show'
                ]);

            /**
             * Adding withdrawal transaction to withdrawal receipt
             */
            Route::post('withdrawal/receipts/{id}/transfer', 'WithdrawalReceiptController@addTransfer')
                ->name('api.withdrawal.receipts.transfer');

            /**
             * Uploading withdrawal receipt proof files
             */
            Route::patch('withdrawal/receipts/{id}/upload/proof/files', 'WithdrawalReceiptController@uploadProofFiles')
                ->name('api.admin.withdrawal.receipts.upload.proof.files');

            /**
             * Exporting withdrawal receipts
             */
            Route::get('withdrawal/receipts/export/{type}', 'WithdrawalReceiptController@export')
                ->name('api.admin.withdrawal.receipts.export');

            /**
             * Getting invoice for withdrawal receipt pdf page
             */
            Route::get('withdrawal/receipts/{id}/pdf', 'WithdrawalReceiptController@viewPdf')
                ->name('api.admin.withdrawal.receipts.pdf');

            /**
             * Downloading invoice for withdrawal receipt pdf file
             */
            Route::get('withdrawal/receipts/{id}/pdf/download', 'WithdrawalReceiptController@downloadPdf')
                ->name('api.admin.withdrawal.receipts.pdf.download');
        });

        /**
         * Navbar namespace
         */
        Route::group(['namespace' => 'Navbar'], function () {

            /**
             * Navbar CRUD
             */
            Route::resource('navbar', 'NavbarController')
                ->parameters([
                    'navbar' => 'id'
                ])
                ->except([
                    'show',
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.navbar.index'
                ]);
        });

        /**
         * Order namespace
         */
        Route::group(['namespace' => 'Order'], function () {

            /**
             * Order items CRUD
             */
            Route::resource('order/items', 'OrderItemController')
                ->parameters([
                    'items' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.order.items.index',
                    'show'  => 'api.admin.order.items.show'
                ]);

            /**
             * Exporting order items
             */
            Route::get('order/items/export/{type}', 'OrderItemController@export')
                ->name('api.admin.order.items.export');

            /**
             * Order overviews CRUD
             */
            Route::resource('order/overviews', 'OrderOverviewController')
                ->parameters([
                    'overviews' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.order.overviews.index',
                    'show'  => 'api.admin.order.overviews.show'
                ]);

            /**
             * Exporting order overviews
             */
            Route::get('order/overviews/export/{type}', 'OrderOverviewController@export')
                ->name('api.admin.order.overviews.export');

            /**
             * Sale overviews CRUD
             */
            Route::resource('sale/overviews', 'SaleOverviewController')
                ->parameters([
                    'overviews' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.sale.overviews.index',
                    'show'  => 'api.admin.sale.overviews.show'
                ]);

            /**
             * Exporting sale overviews
             */
            Route::get('sale/overviews/export/{type}', 'SaleOverviewController@export')
                ->name('api.admin.sale.overviews.export');

            /**
             * Tips CRUD
             */
            Route::resource('tips', 'TipController')
                ->parameters([
                    'tips' => 'id'
                ])
                ->except([
                    'show',
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.tips.index'
                ]);

            /**
             * Exporting tips
             */
            Route::get('tips/export/{type}', 'TipController@export')
                ->name('api.admin.tips.export');

            /**
             * Order item pending requests CRUD
             */
            Route::resource('order/item/pending/requests', 'OrderItemPendingRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.order.item.pending.requests.index',
                    'show'  => 'api.admin.order.item.pending.requests..show'
                ]);

            /**
             * Order item reschedule requests CRUD
             */
            Route::resource('order/item/reschedule/requests', 'OrderItemRescheduleRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.order.item.reschedule.requests.index',
                    'show'  => 'api.admin.order.item.reschedule.requests..show'
                ]);

            /**
             * Order item finish requests CRUD
             */
            Route::resource('order/item/finish/requests', 'OrderItemFinishRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.order.item.finish.requests.index',
                    'show'  => 'api.admin.order.item.finish.requests..show'
                ]);
        });

        /**
         * Place namespace
         */
        Route::group(['namespace' => 'Place'], function () {

            /**
             * Getting google places country autocomplete
             */
            Route::get('places/autocomplete/country', 'PlaceController@autocompleteCountry')
                ->name('api.admin.places.autocomplete.country');

            /**
             * Getting google places region autocomplete
             */
            Route::get('places/autocomplete/region', 'PlaceController@autocompleteRegion')
                ->name('api.admin.places.autocomplete.region');

            /**
             * Getting google places city autocomplete
             */
            Route::get('places/autocomplete/city', 'PlaceController@autocompleteCity')
                ->name('api.admin.places.autocomplete.city');
        });

        /**
         * Request namespace
         */
        Route::group(['namespace' => 'Request'], function () {

            /**
             * User namespace
             */
            Route::group(['namespace' => 'User'], function () {

                /**
                 * User profile request CRUD
                 */
                Route::resource('requests/user/profile/request', 'UserProfileRequestController')
                    ->parameters([
                        'request' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.user.profile.request.index'
                    ]);

                /**
                 * User Id verification request CRUD
                 */
                Route::resource('requests/user/id/verification', 'UserIdVerificationRequestController')
                    ->parameters([
                        'verification' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.user.id.verification.index'
                    ]);

                /**
                 * User deactivation request CRUD
                 */
                Route::resource('requests/user/deactivation', 'UserDeactivationRequestController')
                    ->parameters([
                        'deactivation' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.user.deactivation.index'
                    ]);

                /**
                 * User unsuspend request CRUD
                 */
                Route::resource('requests/user/unsuspend', 'UserUnsuspendRequestController')
                    ->parameters([
                        'unsuspend' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.user.unsuspend.index'
                    ]);

                /**
                 * User deletion request CRUD
                 */
                Route::resource('requests/user/deletion', 'UserDeletionRequestController')
                    ->parameters([
                        'deletion' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.user.deletion.index'
                    ]);
            });

            /**
             * Vybe namespace
             */
            Route::group(['namespace' => 'Vybe'], function () {

                /**
                 * Vybe change request CRUD
                 */
                Route::resource('requests/vybe/change', 'VybeChangeRequestController')
                    ->parameters([
                        'change' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.vybe.change.index'
                    ]);

                /**
                 * Vybe publish request CRUD
                 */
                Route::resource('requests/vybe/publish', 'VybePublishRequestController')
                    ->parameters([
                        'publish' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.vybe.publish.index'
                    ]);

                /**
                 * Vybe unpublish request CRUD
                 */
                Route::resource('requests/vybe/unpublish', 'VybeUnpublishRequestController')
                    ->parameters([
                        'unpublish' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.vybe.unpublish.index'
                    ]);

                /**
                 * Vybe unsuspend request CRUD
                 */
                Route::resource('requests/vybe/unsuspend', 'VybeUnsuspendRequestController')
                    ->parameters([
                        'unsuspend' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.vybe.unsuspend.index'
                    ]);

                /**
                 * Vybe deletion request CRUD
                 */
                Route::resource('requests/vybe/deletion', 'VybeDeletionRequestController')
                    ->parameters([
                        'deletion' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.vybe.deletion.index'
                    ]);
            });

            /**
             * Finance namespace
             */
            Route::group(['namespace' => 'Finance'], function () {

                /**
                 * Billing change request CRUD
                 */
                Route::resource('requests/finance/billing/change', 'BillingChangeRequestController')
                    ->parameters([
                        'change' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.finance.billing.change.index'
                    ]);

                /**
                 * Withdrawal request CRUD
                 */
                Route::resource('requests/finance/withdrawal', 'WithdrawalRequestController')
                    ->parameters([
                        'withdrawal' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.finance.withdrawal.index'
                    ]);

                /**
                 * Payout method request CRUD
                 */
                Route::resource('requests/finance/payout/method', 'PayoutMethodRequestController')
                    ->parameters([
                        'method' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.admin.requests.finance.payout.method.index'
                    ]);
            });
        });

        /**
         * User namespace
         */
        Route::group(['namespace' => 'User'], function () {

            /**
             * Billing setting namespace
             */
            Route::group(['namespace' => 'Billing'], function () {

                /**
                 * Provides getting billing
                 */
                Route::get('users/{id}/billing', 'BillingController@index')
                    ->name('api.admin.users.billing.index');

                /**
                 * Provides updating billing
                 */
                Route::patch('users/{id}/billing', 'BillingController@update')
                    ->name('api.admin.users.billing.update');

                /**
                 * Provides getting billing change request
                 */
                Route::get('users/{id}/billing/change/request', 'BillingChangeRequestController@index')
                    ->name('api.admin.users.billing.change.request.index');

                /**
                 * Provides updating billing change request
                 */
                Route::patch('users/{id}/billing/change/request', 'BillingChangeRequestController@update')
                    ->name('api.admin.users.billing.change.request.update');

                /**
                 * Provides uploading vat number proof files
                 */
                Route::patch('users/billing/vat/number/proof/{vatNumberProofId}/upload/files', 'VatNumberProofController@uploadFiles')
                    ->name('api.admin.users.billing.vat.number.proof.upload.files');

                /**
                 * Provides updating vat number proof status
                 */
                Route::patch('users/billing/vat/number/proof/{vatNumberProofId}/update/status', 'VatNumberProofController@updateStatus')
                    ->name('api.admin.users.billing.vat.number.proof.update.status');

                /**
                 * Provides updating vat number proof exclude tax
                 */
                Route::patch('users/billing/vat/number/proof/{vatNumberProofId}/update/exclude/tax', 'VatNumberProofController@updateExcludeTax')
                    ->name('api.admin.users.billing.vat.number.proof.update.exclude.tax');
            });

            /**
             * Finance namespace
             */
            Route::group(['namespace' => 'Finance'], function () {

                /**
                 * Buyer namespace
                 */
                Route::group(['namespace' => 'Buyer'], function () {

                    /**
                     * Provides getting buyer order overviews
                     */
                    Route::get('users/{id}/finance/buyer/order/overviews', 'OrderOverviewController@index')
                        ->name('api.admin.users.finance.buyer.order.overviews.index');

                    /**
                     * Exporting buyer order overviews
                     */
                    Route::get('users/{id}/finance/buyer/order/overviews/export/{type}', 'OrderOverviewController@export')
                        ->name('api.admin.users.finance.buyer.order.overviews.export');

                    /**
                     * Provides getting buyer order items
                     */
                    Route::get('users/{id}/finance/buyer/order/items', 'OrderItemController@index')
                        ->name('api.admin.users.finance.buyer.order.items.index');

                    /**
                     * Exporting buyer order items
                     */
                    Route::get('users/{id}/finance/buyer/order/items/export/{type}', 'OrderItemController@export')
                        ->name('api.admin.users.finance.buyer.order.items.export');

                    /**
                     * Provides getting buyer invoices
                     */
                    Route::get('users/{id}/finance/buyer/invoices', 'InvoiceController@index')
                        ->name('api.admin.users.finance.buyer.invoices.index');

                    /**
                     * Exporting buyer invoices
                     */
                    Route::get('users/{id}/finance/buyer/invoices/export/{type}', 'InvoiceController@export')
                        ->name('api.admin.users.finance.buyer.invoices.export');

                    /**
                     * Provides getting buyer add funds receipts
                     */
                    Route::get('users/{id}/finance/buyer/add/funds/receipts', 'AddFundsReceiptController@index')
                        ->name('api.admin.users.finance.buyer.add.funds.receipts.index');

                    /**
                     * Exporting buyer add funds receipts
                     */
                    Route::get('users/{id}/finance/buyer/add/funds/receipts/export/{type}', 'AddFundsReceiptController@export')
                        ->name('api.admin.users.finance.buyer.add.funds.receipts.export');

                    /**
                     * Provides getting tip buyer invoices
                     */
                    Route::get('users/{id}/finance/buyer/tip/invoices', 'TipInvoiceController@index')
                        ->name('api.admin.users.finance.buyer.tip.invoices.index');

                    /**
                     * Exporting tip buyer invoices
                     */
                    Route::get('users/{id}/finance/buyer/tip/invoices/export/{type}', 'TipInvoiceController@export')
                        ->name('api.admin.users.finance.buyer.tip.invoices.export');
                });

                /**
                 * Buyer namespace
                 */
                Route::group(['namespace' => 'Seller'], function () {

                    /**
                     * Provides getting seller sale overview
                     */
                    Route::get('users/{id}/finance/seller/sale/overviews', 'SaleOverviewController@index')
                        ->name('api.admin.users.finance.seller.sale.overviews.index');

                    /**
                     * Exporting seller sale overviews
                     */
                    Route::get('users/{id}/finance/seller/sale/overviews/export/{type}', 'SaleOverviewController@export')
                        ->name('api.admin.users.finance.seller.sale.overviews.export');

                    /**
                     * Provides getting seller order items
                     */
                    Route::get('users/{id}/finance/seller/order/items', 'OrderItemController@index')
                        ->name('api.admin.users.finance.seller.order.items.index');

                    /**
                     * Exporting seller order items
                     */
                    Route::get('users/{id}/finance/seller/order/items/export/{type}', 'OrderItemController@export')
                        ->name('api.admin.users.finance.seller.order.items.export');

                    /**
                     * Provides getting seller invoices
                     */
                    Route::get('users/{id}/finance/seller/invoices', 'InvoiceController@index')
                        ->name('api.admin.users.finance.seller.invoices.index');

                    /**
                     * Exporting seller invoices
                     */
                    Route::get('users/{id}/finance/seller/invoices/export/{type}', 'InvoiceController@export')
                        ->name('api.admin.users.finance.seller.invoices.export');

                    /**
                     * Provides getting seller withdrawal receipts
                     */
                    Route::get('users/{id}/finance/seller/withdrawal/receipts', 'WithdrawalReceiptController@index')
                        ->name('api.admin.users.finance.seller.withdrawal.receipts.index');

                    /**
                     * Exporting seller withdrawal receipts
                     */
                    Route::get('users/{id}/finance/seller/withdrawal/receipts/export/{type}', 'WithdrawalReceiptController@export')
                        ->name('api.admin.users.finance.seller.withdrawal.receipts.export');

                    /**
                     * Provides getting tip seller invoices
                     */
                    Route::get('users/{id}/finance/seller/tip/invoices', 'TipInvoiceController@index')
                        ->name('api.admin.users.finance.seller.tip.invoices.index');

                    /**
                     * Exporting seller tip invoices
                     */
                    Route::get('users/{id}/finance/seller/tip/invoices/export/{type}', 'TipInvoiceController@export')
                        ->name('api.admin.users.finance.seller.tip.invoices.export');
                });
            });

            /**
             * User id verification namespace
             */
            Route::group(['namespace' => 'IdVerification'], function () {

                /**
                 * Provides getting user id verification
                 */
                Route::get('users/{id}/id/verification', 'UserIdVerificationController@index')
                    ->name('api.admin.users.id.verification.index');

                /**
                 * Provides updating user id verification
                 */
                Route::patch('users/{id}/id/verification', 'UserIdVerificationController@update')
                    ->name('api.admin.users.id.verification.update');

                /**
                 * Provides updating user id verification request
                 */
                Route::patch('users/{id}/id/verification/request', 'UserIdVerificationRequestController@update')
                    ->name('api.admin.users.id.verification.request.update');
            });

            /**
             * Information namespace
             */
            Route::group(['namespace' => 'Information'], function () {

                /**
                 * Provides getting blocked users
                 */
                Route::get('users/{id}/blocked', 'UserBlockController@index')
                    ->name('api.admin.users.blocked.index');

                /**
                 * Provides deleting blocked user
                 */
                Route::delete('users/{id}/blocked/{blockedUserId}', 'UserBlockController@destroy')
                    ->name('api.admin.users.blocked.destroy');

                /**
                 * Provides getting user subscriptions
                 */
                Route::get('users/{id}/subscriptions', 'SubscriptionController@index')
                    ->name('api.admin.users.subscriptions.index');

                /**
                 * Provides deleting user subscriptions
                 */
                Route::delete('users/{id}/subscriptions/{subscriptionId}', 'SubscriptionController@destroy')
                    ->name('api.admin.users.subscriptions.destroy');

                /**
                 * Provides getting user subscribers
                 */
                Route::get('users/{id}/subscribers', 'SubscriberController@index')
                    ->name('api.admin.users.subscribers.index');

                /**
                 * Provides deleting user subscribers
                 */
                Route::delete('users/{id}/subscribers/{subscriberId}', 'SubscriberController@destroy')
                    ->name('api.admin.users.subscribers.destroy');
            });

            /**
             * Note namespace
             */
            Route::group(['namespace' => 'Note'], function () {

                /**
                 * Provides getting user notes
                 */
                Route::get('users/{id}/notes', 'UserNoteController@index')
                    ->name('api.admin.users.notes.index');

                /**
                 * Provides creating user note
                 */
                Route::post('users/{id}/notes', 'UserNoteController@store')
                    ->name('api.admin.users.notes.store');

                /**
                 * Provides updating user note
                 */
                Route::patch('users/{id}/notes/{userNoteId}', 'UserNoteController@update')
                    ->name('api.admin.users.notes.update');

                /**
                 * Provides deleting user note
                 */
                Route::delete('users/{id}/notes/{userNoteId}', 'UserNoteController@destroy')
                    ->name('api.admin.users.notes.destroy');
            });

            /**
             * Payout request namespace
             */
            Route::group(['namespace' => 'Payout'], function () {

                /**
                 * Provides getting payout method
                 */
                Route::get('users/{id}/payout/methods', 'PayoutMethodController@index')
                    ->name('api.admin.users.payout.methods.index');

                /**
                 * Provides creating payout method
                 */
                Route::post('users/{id}/payout/methods', 'PayoutMethodController@store')
                    ->name('api.admin.users.payout.methods.store');

                /**
                 * Provides updating payout method
                 */
                Route::patch('users/{id}/payout/methods', 'PayoutMethodController@update')
                    ->name('api.admin.users.payout.methods.update');

                /**
                 * Provides detaching payout method
                 */
                Route::delete('users/{id}/payout/methods/{methodId}', 'PayoutMethodController@destroy')
                    ->name('api.admin.users.payout.methods.destroy');

                /**
                 * Provides updating payout method request
                 */
                Route::patch('users/{id}/payout/method/requests', 'PayoutMethodRequestController@update')
                    ->name('api.admin.users.payout.method.request.update');
            });

            /**
             * Request namespace
             */
            Route::group(['namespace' => 'Request'], function () {

                /**
                 * Deactivation namespace
                 */
                Route::group(['namespace' => 'Deactivation'], function () {

                    /**
                     * Provides getting deactivation request
                     */
                    Route::get('users/{id}/deactivation/request', 'UserDeactivationRequestController@index')
                        ->name('api.admin.users.deactivation.request.index');

                    /**
                     * Provides updating deactivation request
                     */
                    Route::patch('users/{id}/deactivation/request', 'UserDeactivationRequestController@update')
                        ->name('api.admin.users.deactivation.request.update');
                });

                /**
                 * Deletion namespace
                 */
                Route::group(['namespace' => 'Deletion'], function () {

                    /**
                     * Provides getting deletion request
                     */
                    Route::get('users/{id}/deletion/request', 'UserDeletionRequestController@index')
                        ->name('api.admin.users.deletion.request.index');

                    /**
                     * Provides updating deletion request
                     */
                    Route::patch('users/{id}/deletion/request', 'UserDeletionRequestController@update')
                        ->name('api.admin.users.deletion.request.update');
                });

                /**
                 * Profile namespace
                 */
                Route::group(['namespace' => 'Profile'], function () {

                    /**
                     * Provides getting profile request
                     */
                    Route::get('users/{id}/profile/request', 'UserProfileRequestController@index')
                        ->name('api.admin.users.profile.request.index');

                    /**
                     * Provides updating profile request
                     */
                    Route::patch('users/{id}/profile/request', 'UserProfileRequestController@update')
                        ->name('api.admin.users.profile.request.update');

                    /**
                     * Provides accepting profile request
                     */
                    Route::post('users/{id}/profile/request/accept', 'UserProfileRequestController@acceptAll')
                        ->name('api.admin.users.profile.request.accept');

                    /**
                     * Provides declining profile request
                     */
                    Route::post('users/{id}/profile/request/decline', 'UserProfileRequestController@declineAll')
                        ->name('api.admin.users.profile.request.decline');
                });

                /**
                 * Unsuspend namespace
                 */
                Route::group(['namespace' => 'Unsuspend'], function () {

                    /**
                     * Provides getting unsuspend request
                     */
                    Route::get('users/{id}/unsuspend/request', 'UserUnsuspendRequestController@index')
                        ->name('api.admin.users.unsuspend.request.index');

                    /**
                     * Provides updating unsuspend request
                     */
                    Route::patch('users/{id}/unsuspend/request', 'UserUnsuspendRequestController@update')
                        ->name('api.admin.users.unsuspend.request.update');
                });
            });

            /**
             * Setting namespace
             */
            Route::group(['namespace' => 'Setting'], function () {

                /**
                 * Getting user custom settings
                 */
                Route::get('users/{userId}/settings', 'UserSettingController@index')
                    ->name('api.admin.users.custom.settings.index');

                /**
                 * Updating user custom settings
                 */
                Route::patch('users/{userId}/settings', 'UserSettingController@update')
                    ->name('api.admin.users.settings.update');
            });

            /**
             * Statistic namespace
             */
            Route::group(['namespace' => 'Statistic'], function () {

                /**
                 * Provides getting user statistics
                 */
                Route::get('users/{id}/statistics', 'StatisticController@index')
                    ->name('api.admin.users.statistics.index');
            });

            /**
             * User namespace
             */
            Route::group(['namespace' => 'User'], function () {

                /**
                 * Users CRUD routes
                 */
                Route::resource('users', 'UserController')
                    ->parameters([
                        'users' => 'id'
                    ])
                    ->except([
                        'store',
                        'create',
                        'edit'
                    ])
                    ->names([
                        'index'   => 'api.admin.users.index',
                        'show'    => 'api.admin.users.show',
                        'update'  => 'api.admin.users.update',
                        'destroy' => 'api.admin.users.destroy'
                    ]);

                /**
                 * Providing password reset initialize
                 */
                Route::get('users/{id}/password/reset/initialize', 'UserController@initializePasswordReset')
                    ->name('api.admin.users.password.reset.initialize');
            });

            /**
             * Vybe namespace
             */
            Route::group(['namespace' => 'Vybe'], function () {

                /**
                 * Provides getting user vybes
                 */
                Route::get('users/{id}/vybes', 'VybeController@index')
                    ->name('api.admin.users.vybes.index');
            });
        });

        /**
         * Vybe namespace
         */
        Route::group(['namespace' => 'Vybe'], function () {

            /**
             * Vybes CRUD routes
             */
            Route::resource('vybes', 'VybeController')
                ->parameters([
                    'vybes' => 'id'
                ])
                ->except([
                    'create',
                    'edit'
                ])
                ->names([
                    'index'    => 'api.admin.vybes.index',
                    'store'    => 'api.admin.vybes.store',
                    'show'     => 'api.admin.vybes.show',
                    'update'   => 'api.admin.vybes.update',
                    'destroy'  => 'api.admin.vybes.destroy'
                ]);

            /**
             * Getting vybe form data
             */
            Route::get('vybes/form/user/{id}', 'VybeController@getFormData')
                ->name('api.admin.vybes.form.user');

            /**
             * Vybe publish requests CRUD routes
             */
            Route::resource('vybe/publish/requests', 'VybePublishRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'index',
                    'store',
                    'create',
                    'edit',
                    'destroy'
                ])
                ->names([
                    'show'   => 'api.admin.vybe.publish.requests.show',
                    'update' => 'api.admin.vybe.publish.requests.update'
                ]);

            /**
             * Vybe change requests CRUD routes
             */
            Route::resource('vybe/change/requests', 'VybeChangeRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'index',
                    'store',
                    'create',
                    'edit',
                    'destroy'
                ])
                ->names([
                    'show'   => 'api.admin.vybe.change.requests.show',
                    'update' => 'api.admin.vybe.change.requests.update'
                ]);

            /**
             * Vybe unsuspend requests CRUD routes
             */
            Route::resource('vybe/unsuspend/requests', 'VybeUnsuspendRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'index',
                    'store',
                    'create',
                    'edit',
                    'destroy'
                ])
                ->names([
                    'show'   => 'api.admin.vybe.unsuspend.requests.show',
                    'update' => 'api.admin.vybe.unsuspend.requests.update'
                ]);

            /**
             * Vybe deletion requests CRUD routes
             */
            Route::resource('vybe/deletion/requests', 'VybeDeletionRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'index',
                    'store',
                    'create',
                    'edit',
                    'destroy'
                ])
                ->names([
                    'show'   => 'api.admin.vybe.deletion.requests.show',
                    'update' => 'api.admin.vybe.deletion.requests.update'
                ]);

            /**
             * Vybe unpublish requests CRUD routes
             */
            Route::resource('vybe/unpublish/requests', 'VybeUnpublishRequestController')
                ->parameters([
                    'requests' => 'id'
                ])
                ->except([
                    'index',
                    'store',
                    'create',
                    'edit',
                    'destroy'
                ])
                ->names([
                    'show'   => 'api.admin.vybe.unpublish.requests.show',
                    'update' => 'api.admin.vybe.unpublish.requests.update'
                ]);

            /**
             * Vybe versions CRUD routes
             */
            Route::resource('vybe/versions', 'VybeVersionController')
                ->parameters([
                    'versions' => 'id'
                ])
                ->except([
                    'index',
                    'store',
                    'create',
                    'update',
                    'edit',
                    'destroy'
                ])
                ->names([
                    'show' => 'api.admin.vybe.versions.show'
                ]);
        });
    });
});
