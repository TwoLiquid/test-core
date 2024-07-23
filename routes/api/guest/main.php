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
     * Guest API routes
     */
    Route::group([
        'namespace'  => 'Guest',
        'middleware' => ['localization']
    ], function () {

        /**
         * Activity namespace routes
         */
        Route::group(['namespace' => 'Activity'], function () {

            /**
             * Activities CRUD
             */
            Route::resource('activities', 'ActivityController')
                ->parameters([
                    'activities' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'delete'
                ])
                ->names([
                    'index' => 'api.activities.index',
                    'show'  => 'api.activities.show'
                ]);

            /**
             * Getting activities by category
             */
            Route::get('category/{id}/activities', 'ActivityController@getByCategory')
                ->name('api.category.activities');

            /**
             * Getting activities by categories
             */
            Route::get('categories/activities/multiple', 'ActivityController@getByCategories')
                ->name('api.categories.activities.multiple');

            /**
             * Activity tags CRUD
             */
            Route::resource('activity/tags', 'ActivityTagController')
                ->parameters([
                    'tags' => 'id'
                ])
                ->except([
                    'create',
                    'edit',
                    'store',
                    'update',
                    'delete'
                ])
                ->names([
                    'index' => 'api.activity.tags.index',
                    'show'  => 'api.activity.tags.show'
                ]);
        });

        /**
         * AddFunds namespace routes
         */
        Route::group(['namespace' => 'AddFunds', 'prefix' => 'add/funds'], function () {

            /**
             * Add funds receipt statuses CRUD
             */
            Route::resource('receipt/statuses', 'AddFundsReceiptStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.add.funds.receipt.statuses.index',
                    'show'  => 'api.add.funds.receipt.statuses.show'
                ]);
        });

        /**
         * Admin namespace routes
         */
        Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

            /**
             * Admin statuses CRUD
             */
            Route::resource('statuses', 'AdminStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.admin.statuses.index',
                    'show'  => 'api.admin.statuses.show'
                ]);
        });

        /**
         * Alerts namespace
         */
        Route::group(['namespace' => 'Alert'], function () {

            /**
             * Alert aligns CRUD
             */
            Route::resource('alert/aligns', 'AlertAlignController')
                ->parameters([
                    'aligns' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.alert.aligns.index',
                    'show'  => 'api.alert.aligns.show'
                ]);

            /**
             * Alert animations CRUD
             */
            Route::resource('alert/animations', 'AlertAnimationController')
                ->parameters([
                    'animations' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.alert.animations.index',
                    'show'  => 'api.alert.animations.show'
                ]);

            /**
             * Alert covers CRUD
             */
            Route::resource('alert/covers', 'AlertCoverController')
                ->parameters([
                    'covers' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.alert.covers.index',
                    'show'  => 'api.alert.covers.show'
                ]);

            /**
             * Alert logo align CRUD
             */
            Route::resource('alert/logo/aligns', 'AlertLogoAlignController')
                ->parameters([
                    'aligns' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.alert.logo.aligns.index',
                    'show'  => 'api.alert.logo.aligns.show'
                ]);

            /**
             * Alert text fonts CRUD
             */
            Route::resource('alert/text/fonts', 'AlertTextFontController')
                ->parameters([
                    'fonts' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.alert.text.fonts.index',
                    'show'  => 'api.alert.text.fonts.show'
                ]);

            /**
             * Alert text styles CRUD
             */
            Route::resource('alert/text/styles', 'AlertTextStyleController')
                ->parameters([
                    'styles' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.alert.text.styles.index',
                    'show'  => 'api.alert.text.styles.show'
                ]);

            /**
             * Alert types CRUD
             */
            Route::resource('alert/types', 'AlertTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.alert.types.index',
                    'show'  => 'api.alert.types.show'
                ]);
        });

        /**
         * Catalog namespace
         */
        Route::group(['namespace' => 'Catalog', 'prefix' => 'catalog'], function () {

            /**
             * Getting categories for navbar
             */
            Route::get('categories/for/navbar', 'CategoryController@getCategoriesForNavbar')
                ->name('api.catalog.categories.for.navbar');

            /**
             * Getting category by code
             */
            Route::get('category/by/code', 'CategoryController@getByCode')
                ->name('api.catalog.category.by.code');

            /**
             * Getting activities by category
             */
            Route::get('category/{id}/activities', 'ActivityController@getByCategory')
                ->name('api.catalog.category.activities');

            /**
             * Getting subcategory by code
             */
            Route::get('subcategory/by/code', 'SubcategoryController@getByCode')
                ->name('api.catalog.subcategory.by.code');

            /**
             * Getting a related by category activity with its vybes
             */
            Route::get('activities/{id}/related', 'ActivityController@getRelatedActivity')
                ->name('api.catalog.activity.related');

            /**
             * Getting popular activities
             */
            Route::get('activities/popular', 'ActivityController@getPopularActivities')
                ->name('api.catalog.activities.popular');

            /**
             * Getting an activity by code
             */
            Route::get('activity/by/code', 'ActivityController@getByCode')
                ->name('api.catalog.activity.by.code');

            /**
             * Getting vybes form
             */
            Route::get('vybes/form', 'VybeController@getForm')
                ->name('api.catalog.vybes.form');

            /**
             * Getting vybes and filters values by filters
             */
            Route::get('vybes/with/filters', 'VybeController@searchWithFilters')
                ->name('api.catalog.vybes.search.with.filters');
        });

        /**
         * Category namespace routes
         */
        Route::group(['namespace' => 'Category'], function () {

            /**
             * Categories CRUD
             */
            Route::resource('categories', 'CategoryController')
                ->parameters([
                    'categories' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'delete'
                ])
                ->names([
                    'index' => 'api.categories.index',
                    'show'  => 'api.categories.show'
                ]);

            /**
             * Getting category subcategories
             */
            Route::get('categories/{id}/subcategories', 'CategoryController@getByCategory')
                ->name('api.categories.subcategories');

            /**
             * Getting categories subcategories
             */
            Route::get('categories/subcategories/multiple', 'CategoryController@getByCategories')
                ->name('api.categories.subcategories.multiple');
        });

        /**
         * Place namespace routes
         */
        Route::group(['namespace' => 'Place'], function () {

            /**
             * Getting all country places
             */
            Route::get('places/countries', 'CountryPlaceController@index')
                ->name('api.places.countries.index');

            /**
             * Getting country place
             */
            Route::get('places/countries/{id}', 'CountryPlaceController@show')
                ->name('api.places.countries.show');

            /**
             * Getting all region places by country place
             */
            Route::get('places/countries/{countryId}/regions', 'RegionPlaceController@index')
                ->name('api.places.countries.regions.index');
        });

        /**
         * Currency namespace routes
         */
        Route::group(['namespace' => 'Currency'], function () {

            /**
             * Currencies CRUD routes
             */
            Route::resource('currencies', 'CurrencyController')
                ->parameters([
                    'currencies' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.currencies.index',
                    'show'  => 'api.currencies.show'
                ]);
        });

        /**
         * Device namespace routes
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
                    'store',
                    'create',
                    'edit',
                    'update',
                    'delete'
                ])
                ->names([
                    'index' => 'api.devices.index',
                    'show'  => 'api.devices.show'
                ]);

            /**
             * Getting devices by activity
             */
            Route::get('devices/activities/{id}', 'DeviceController@getByActivity')
                ->name('api.devices.activities');
        });

        /**
         * Gender namespace
         */
        Route::group(['namespace' => 'Gender'], function () {

            /**
             * Genders CRUD
             */
            Route::resource('genders', 'GenderController')
                ->parameters([
                    'genders' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.genders.index',
                    'show'  => 'api.genders.show'
                ]);
        });

        /**
         * Home namespace
         */
        Route::group(['namespace' => 'Home', 'prefix' => 'home'], function () {

            /**
             * Getting categories
             */
            Route::get('categories', 'CategoryController@index')
                ->name('api.home.categories');

            /**
             * Getting activities
             */
            Route::get('activities', 'ActivityController@index')
                ->name('api.home.activities');

            /**
             * Getting top creators
             */
            Route::get('top/creators', 'UserController@index')
                ->name('api.home.top.creators');
        });

        /**
         * Invoice namespace
         */
        Route::group(['namespace' => 'Invoice'], function () {

            /**
             * Invoice statuses CRUD
             */
            Route::resource('invoice/statuses', 'InvoiceStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.invoice.statuses.index',
                    'show'  => 'api.invoice.statuses.show'
                ]);

            /**
             * Invoice types CRUD
             */
            Route::resource('invoice/types', 'InvoiceTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.invoice.types.index',
                    'show'  => 'api.invoice.types.show'
                ]);
        });

        /**
         * Languages namespace
         */
        Route::group(['namespace' => 'Language'], function () {

            /**
             * Languages CRUD
             */
            Route::resource('languages', 'LanguageController')
                ->parameters([
                    'languages' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.languages.index',
                    'show'  => 'api.languages.show'
                ]);

            /**
             * Language levels CRUD
             */
            Route::resource('language/levels', 'LanguageLevelController')
                ->parameters([
                    'levels' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.language.levels.index',
                    'show'  => 'api.language.levels.show'
                ]);
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
                    'forms' => 'id'
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
                    'index' => 'api.main.navbar.index'
                ]);
        });

        /**
         * Notification namespace routes
         */
        Route::group(['namespace' => 'Notification'], function () {

            /**
             * Notification settings CRUD
             */
            Route::resource('notification/settings', 'NotificationSettingController')
                ->parameters([
                    'settings' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.notification.settings.index',
                    'show'  => 'api.notification.settings.show'
                ]);

            /**
             * Notification setting categories CRUD
             */
            Route::resource('notification/setting/categories', 'NotificationSettingCategoryController')
                ->parameters([
                    'categories' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.notification.setting.categories.index',
                    'show'  => 'api.notification.setting.categories.show'
                ]);

            /**
             * Notification setting subcategories CRUD
             */
            Route::resource('notification/setting/subcategories', 'NotificationSettingSubcategoryController')
                ->parameters([
                    'subcategories' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.notification.setting.subcategories.index',
                    'show'  => 'api.notification.setting.subcategories.show'
                ]);
        });

        /**
         * Order namespace routes
         */
        Route::group(['namespace' => 'Order'], function () {

            /**
             * Order item statuses CRUD
             */
            Route::resource('order/item/statuses', 'OrderItemStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.order.item.statuses.index',
                    'show'  => 'api.order.item.statuses.show'
                ]);

            /**
             * Order item payment statuses CRUD
             */
            Route::resource('order/item/payment/statuses', 'OrderItemPaymentStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.order.item.payment.statuses.index',
                    'show'  => 'api.order.item.payment.statuses.show'
                ]);

            /**
             * Order item request actions CRUD
             */
            Route::resource('order/item/request/actions', 'OrderItemRequestActionController')
                ->parameters([
                    'actions' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.order.item.request.actions.index',
                    'show'  => 'api.order.item.request.actions.show'
                ]);

            /**
             * Order item request initiators CRUD
             */
            Route::resource('order/item/request/initiators', 'OrderItemRequestInitiatorController')
                ->parameters([
                    'initiators' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.order.item.request.initiators.index',
                    'show'  => 'api.order.item.request.initiators.show'
                ]);

            /**
             * Order item request statuses CRUD
             */
            Route::resource('order/item/request/statuses', 'OrderItemRequestStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.order.item.request.statuses.index',
                    'show'  => 'api.order.item.request.statuses.show'
                ]);

            /**
             * Order item purchase sort by CRUD
             */
            Route::resource('order/item/purchase/sort/by', 'OrderItemPurchaseSortByController')
                ->parameters([
                    'by' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.order.item.purchase.sort.by.index',
                    'show'  => 'api.order.item.purchase.sort.by.show'
                ]);

            /**
             * Order item sale sort by CRUD
             */
            Route::resource('order/item/sale/sort/by', 'OrderItemSaleSortByController')
                ->parameters([
                    'by' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.order.item.sale.sort.by.index',
                    'show'  => 'api.order.item.sale.sort.by.show'
                ]);
        });

        /**
         * Payment namespace routes
         */
        Route::group(['namespace' => 'Payment'], function () {

            /**
             * Payment types CRUD
             */
            Route::resource('payment/types', 'PaymentTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.payment.types.index',
                    'show'  => 'api.payment.types.show'
                ]);

            /**
             * Payment method field types CRUD
             */
            Route::resource('payment/method/field/types', 'PaymentMethodFieldTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.payment.method.field.types.index',
                    'show'  => 'api.payment.method.field.types.show'
                ]);

            /**
             * Payment method payment statuses CRUD
             */
            Route::resource('payment/method/payment/statuses', 'PaymentMethodPaymentStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.payment.method.payment.statuses.index',
                    'show'  => 'api.payment.method.payment.statuses.show'
                ]);

            /**
             * Payment method withdrawal statuses CRUD
             */
            Route::resource('payment/method/withdrawal/statuses', 'PaymentMethodWithdrawalStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.payment.method.withdrawal.statuses.index',
                    'show'  => 'api.payment.method.withdrawal.statuses.show'
                ]);
        });

        /**
         * PersonalityTrait namespace
         */
        Route::group(['namespace' => 'PersonalityTrait'], function () {

            /**
             * Personality traits CRUD
             */
            Route::resource('personality/traits', 'PersonalityTraitController')
                ->parameters([
                    'traits' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.personality.traits.index',
                    'show'  => 'api.personality.traits.show'
                ]);
        });

        /**
         * Phone codes namespace
         */
        Route::group(['namespace' => 'PhoneCode'], function () {

            /**
             * Phone codes CRUD
             */
            Route::resource('phone/codes', 'PhoneCodeController')
                ->parameters([
                    'codes' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.phone.codes.index',
                    'show'  => 'api.phone.codes.show'
                ]);
        });

        /**
         * Platform namespace routes
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
                    'store',
                    'create',
                    'edit',
                    'update',
                    'delete'
                ])
                ->names([
                    'index' => 'api.platforms.index',
                    'show'  => 'api.platforms.show'
                ]);

            /**
             * Getting platforms by activity
             */
            Route::get('platforms/activities/{id}', 'PlatformController@getByActivity')
                ->name('api.platforms.activities');
        });

        /**
         * Profile namespace
         */
        Route::group(['namespace' => 'Profile'], function () {

            /**
             * Getting user profile home
             */
            Route::get('profiles/{username}', 'HomeController@index')
                ->name('api.profiles');

            /**
             * Getting user profile vybes
             */
            Route::get('profiles/{username}/vybes', 'HomeController@getVybes')
                ->name('api.profiles.vybes.index');

            /**
             * Getting user profile favorite vybes
             */
            Route::get('profiles/{username}/favorite/vybes', 'HomeController@getFavoriteVybes')
                ->name('api.profiles.favorite.vybes.index');

            /**
             * Get user profile subscriptions
             */
            Route::get('profiles/{username}/subscriptions', 'HomeController@getSubscriptions')
                ->name('api.profiles.subscriptions');

            /**
             * Get user profile subscribers
             */
            Route::get('profiles/{username}/subscribers', 'HomeController@getSubscribers')
                ->name('api.profiles.subscribers');

            /**
             * Getting profile vybe
             */
            Route::get('profiles/{username}/vybes/{id}', 'VybeController@show')
                ->name('api.profiles.vybes.show');

            /**
             * Getting profile vybe timeslot users
             */
            Route::get('profiles/vybes/timeslot/{id}/users', 'VybeController@getTimeslotUsers')
                ->name('api.profiles.vybes.timeslot.users');
        });

        /**
         * Request namespace
         */
        Route::group(['namespace' => 'Request'], function () {

            /**
             * Request field statuses CRUD
             */
            Route::resource('request/field/statuses', 'RequestFieldStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.request.field.statuses.index',
                    'show'  => 'api.request.field.statuses.show'
                ]);

            /**
             * Request groups CRUD
             */
            Route::resource('request/groups', 'RequestGroupController')
                ->parameters([
                    'groups' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.request.groups.index',
                    'show'  => 'api.request.groups.show'
                ]);

            /**
             * Request statuses CRUD
             */
            Route::resource('request/statuses', 'RequestStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.request.statuses.index',
                    'show'  => 'api.request.statuses.show'
                ]);

            /**
             * Request types CRUD
             */
            Route::resource('request/types', 'RequestTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.request.types.index',
                    'show'  => 'api.request.types.show'
                ]);
        });

        /**
         * Search namespace
         */
        Route::group(['namespace' => 'Search'], function () {

            /**
             * Provides global search
             */
            Route::get('search/global', 'SearchController@globalSearch')
                ->name('api.search.global');

            /**
             * Provides search users
             */
            Route::get('search/users', 'UserController@index')
                ->name('api.search.users');

            /**
             * Provides search activities
             */
            Route::get('search/activities', 'ActivityController@index')
                ->name('api.search.activities');

            /**
             * Provides search vybes
             */
            Route::get('search/vybes', 'VybeController@index')
                ->name('api.search.vybes');
        });

        /**
         * Setting namespace
         */
        Route::group(['namespace' => 'Setting'], function () {

            /**
             * Type namespace routes
             */
            Route::group(['namespace' => 'Type'], function () {

                /**
                 * Setting types CRUD
                 */
                Route::resource('setting/types', 'SettingTypeController')
                    ->parameters([
                        'types' => 'id'
                    ])
                    ->except([
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.setting.types.index',
                        'show'  => 'api.setting.types.show'
                    ]);
            });
        });

        /**
         * Timezone namespace
         */
        Route::group(['namespace' => 'Timezone'], function () {

            /**
             * Timezones CRUD
             */
            Route::resource('timezones', 'TimezoneController')
                ->parameters([
                    'timezones' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.timezones.index',
                    'show'  => 'api.timezones.show'
                ]);
        });

        /**
         * Toast message type namespace
         */
        Route::group(['namespace' => 'ToastMessage'], function () {

            /**
             * Toast message types CRUD
             */
            Route::resource('toast/message/types', 'ToastMessageTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.toast.message.types.index',
                    'show'  => 'api.toast.message.types.show'
                ]);
        });

        /**
         * Unit namespace
         */
        Route::group(['namespace' => 'Unit'], function () {

            /**
             * Units CRUD routes
             */
            Route::resource('units', 'UnitController')
                ->parameters([
                    'units' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.units.index',
                    'show'  => 'api.units.show'
                ]);

            /**
             * Getting activities by category
             */
            Route::get('units/activities/multiple', 'UnitController@getByActivities')
                ->name('api.units.activities.multiple');

            /**
             * Event units CRUD routes
             */
            Route::resource('event/units', 'EventUnitController')
                ->parameters([
                    'units' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.event.units.index',
                    'show'  => 'api.event.units.show'
                ]);

            /**
             * Unit types CRUD routes
             */
            Route::resource('unit/types', 'UnitTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.unit.types.index',
                    'show'  => 'api.unit.types.show'
                ]);
        });

        /**
         * User namespace
         */
        Route::group(['namespace' => 'User'], function () {

            /**
             * User balance types CRUD routes
             */
            Route::resource('user/balance/types', 'UserBalanceTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.user.balance.types.index',
                    'show'  => 'api.user.balance.types.show'
                ]);

            /**
             * User balance statuses CRUD routes
             */
            Route::resource('user/balance/statuses', 'UserBalanceStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.user.balance.statuses.index',
                    'show'  => 'api.user.balance.statuses.show'
                ]);

            /**
             * User state status CRUD routes
             */
            Route::resource('user/state/statuses', 'UserStateStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'store',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.user.state.statuses.index',
                    'show'  => 'api.user.state.statuses.show'
                ]);

            /**
             * User id verification statuses CRUD routes
             */
            Route::resource('user/id/verification/statuses', 'UserIdVerificationStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.user.id.verification.statuses.index',
                    'show'  => 'api.user.id.verification.statuses.show'
                ]);

            /**
             * User labels CRUD
             */
            Route::resource('user/labels', 'UserLabelController')
                ->parameters([
                    'labels' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.user.labels.index',
                    'show'  => 'api.user.labels.show'
                ]);

            /**
             * User account statuses CRUD
             */
            Route::resource('user/account/statuses', 'AccountStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.user.account.statuses.index',
                    'show'  => 'api.user.account.statuses.show'
                ]);

            /**
             * User themes CRUD
             */
            Route::resource('user/themes', 'UserThemeController')
                ->parameters([
                    'themes' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.user.themes.index',
                    'show'  => 'api.user.themes.show'
                ]);
        });

        /**
         * Vat number proof namespace routes
         */
        Route::group(['namespace' => 'VatNumberProof'], function () {

            /**
             * Vat number proof statuses CRUD
             */
            Route::resource('vat/number/proof/statuses', 'VatNumberProofStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vat.number.proof.statuses.index',
                    'show'  => 'api.vat.number.proof.statuses.show'
                ]);
        });

        /**
         * Vybe namespace
         */
        Route::group(['namespace' => 'Vybe'], function () {

            /**
             * Getting vybe calendar
             */
            Route::get('vybes/{id}/calendar', 'VybeController@getCalendar')
                ->name('api.vybes.calendar');

            /**
             * Vybe accesses CRUD
             */
            Route::resource('vybe/accesses', 'VybeAccessController')
                ->parameters([
                    'accesses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.accesses.index',
                    'show'  => 'api.vybe.accesses.show'
                ]);

            /**
             * Vybe age limit CRUD
             */
            Route::resource('vybe/age/limits', 'VybeAgeLimitController')
                ->parameters([
                    'limits' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.age.limits.index',
                    'show'  => 'api.vybe.age.limits.show'
                ]);

            /**
             * Vybe appearances CRUD
             */
            Route::resource('vybe/appearances', 'VybeAppearanceController')
                ->parameters([
                    'appearances' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.appearances.index',
                    'show'  => 'api.vybe.appearances.show'
                ]);

            /**
             * Vybe order accepts CRUD
             */
            Route::resource('vybe/order/accepts', 'VybeOrderAcceptController')
                ->parameters([
                    'accepts' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.order.accepts.index',
                    'show'  => 'api.vybe.order.accepts.show'
                ]);

            /**
             * Vybe periods CRUD
             */
            Route::resource('vybe/periods', 'VybePeriodController')
                ->parameters([
                    'periods' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.periods.index',
                    'show'  => 'api.vybe.periods.show'
                ]);

            /**
             * Vybe showcases CRUD
             */
            Route::resource('vybe/showcases', 'VybeShowcaseController')
                ->parameters([
                    'showcases' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.showcases.index',
                    'show'  => 'api.vybe.showcases.show'
                ]);

            /**
             * Vybe sorts CRUD
             */
            Route::resource('vybe/sorts', 'VybeSortController')
                ->parameters([
                    'sorts' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.sorts.index',
                    'show'  => 'api.vybe.sorts.show'
                ]);

            /**
             * Vybe statuses CRUD
             */
            Route::resource('vybe/statuses', 'VybeStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index'   => 'api.vybe.statuses.index',
                    'show'    => 'api.vybe.statuses.show',
                    'store'   => 'api.vybe.statuses.store',
                    'update'  => 'api.vybe.statuses.update',
                    'destroy' => 'api.vybe.statuses.destroy'
                ]);

            /**
             * Vybe steps CRUD
             */
            Route::resource('vybe/steps', 'VybeStepController')
                ->parameters([
                    'steps' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index'   => 'api.vybe.steps.index',
                    'show'    => 'api.vybe.steps.show',
                    'store'   => 'api.vybe.steps.store',
                    'update'  => 'api.vybe.steps.update',
                    'destroy' => 'api.vybe.steps.destroy'
                ]);

            /**
             * Vybe types CRUD
             */
            Route::resource('vybe/types', 'VybeTypeController')
                ->parameters([
                    'types' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.vybe.types.index',
                    'show'  => 'api.vybe.types.show'
                ]);
        });

        /**
         * Withdrawal namespace routes
         */
        Route::group(['namespace' => 'Withdrawal', 'prefix' => 'withdrawal'], function () {

            /**
             * Withdrawal receipt statuses CRUD
             */
            Route::resource('receipt/statuses', 'WithdrawalReceiptStatusController')
                ->parameters([
                    'statuses' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.withdrawal.receipt.statuses.index',
                    'show'  => 'api.withdrawal.receipt.statuses.show'
                ]);
        });
    });
});
