<?php

use Illuminate\Support\Facades\Route;
use App\Lists\Vybe\Step\VybeStepList;

/*
|--------------------------------------------------------------------------
| API General Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {

    /**
     * General API routes
     */
    Route::group([
        'namespace'  => 'General',
        'middleware' => ['gateway', 'auth.user', 'localization']
    ], function () {

        /**
         * Alerts namespace
         */
        Route::group(['namespace' => 'Alert'], function () {

            /**
             * Alerts CRUD
             */
            Route::resource('alerts', 'AlertController')
                ->parameters([
                    'alerts' => 'id'
                ])
                ->except([
                    'show',
                    'create',
                    'store',
                    'edit',
                    'destroy'
                ])
                ->names([
                    'index'  => 'api.alerts.index',
                    'update' => 'api.alerts.update'
                ]);
        });

        /**
         * Cart namespace
         */
        Route::group(['namespace' => 'Cart'], function () {

            /**
             * Cart CRUD
             */
            Route::resource('cart', 'CartController')
                ->parameters([
                    'referrals' => 'id'
                ])
                ->except([
                    'show',
                    'create',
                    'store',
                    'edit',
                    'update'
                ])
                ->names([
                    'index'   => 'api.cart.index',
                    'destroy' => 'api.cart.destroy'
                ]);

            /**
             * Updating user cart items
             */
            Route::patch('cart/refresh/all', 'CartController@refresh')
                ->name('api.cart.refresh.all');

            /**
             * Provides cart checkout
             */
            Route::post('cart/checkout', 'CartController@checkout');

            /**
             * Provides cart checkout executing
             */
            Route::post('cart/checkout/execute', 'CartController@checkoutExecute');

            /**
             * Provides cart checkout canceling
             */
            Route::post('cart/checkout/cancel', 'CartController@checkoutCancel');
        });

        /**
         * Dashboard namespace
         */
        Route::group(['namespace' => 'Dashboard'], function () {

            /**
             * Profile namespace
             */
            Route::group(['namespace' => 'Profile'], function () {

                /**
                 * Getting user profile
                 */
                Route::get('dashboard/profile', 'ProfileController@index')
                    ->name('api.dashboard.profile.index');

                /**
                 * Providing checking username is already registered
                 */
                Route::post('dashboard/profile/check/username', 'ProfileController@checkUsername')
                    ->name('api.dashboard.profile.check.username');

                /**
                 * Providing checking user email is already registered
                 */
                Route::post('dashboard/profile/check/email', 'ProfileController@checkEmail')
                    ->name('api.dashboard.profile.check.email');

                /**
                 * Updating user profile
                 */
                Route::patch('dashboard/profile', 'ProfileController@update')
                    ->name('api.dashboard.profile.update');

                /**
                 * Closing declined profile request
                 */
                Route::patch('dashboard/profile/request/close', 'ProfileController@close')
                    ->name('api.dashboard.profile.request.close');

                /**
                 * Cancelling profile request
                 */
                Route::delete('dashboard/profile/request/cancel', 'ProfileController@cancel')
                    ->name('api.dashboard.profile.request.cancel');
            });

            /**
             * Vybe namespace
             */
            Route::group(['namespace' => 'Vybe'], function () {

                /**
                 * Getting user favorite vybes
                 */
                Route::get('dashboard/favorite/vybes', 'FavoriteVybeController@index')
                    ->name('api.dashboard.favorite.vybes.index');

                /**
                 * Getting more user favorite vybes
                 */
                Route::get('dashboard/favorite/vybes/more', 'FavoriteVybeController@getMoreFavoriteVybes')
                    ->name('api.dashboard.favorite.vybes.more.index');

                /**
                 * Getting user vybes
                 */
                Route::get('dashboard/vybes', 'VybeController@index')
                    ->name('api.dashboard.vybes.index');

                /**
                 * Getting more user vybes
                 */
                Route::get('dashboard/vybes/more', 'VybeController@getMoreVybes')
                    ->name('api.dashboard.vybes.more.index');

                /**
                 * Deleting vybe
                 */
                Route::delete('dashboard/vybes/{id}', 'VybeController@deleteUncompleted')
                    ->name('api.dashboard.vybes.delete');
            });

            /**
             * Purchase namespace
             */
            Route::group(['namespace' => 'Purchase'], function () {

                /**
                 * Getting user purchases
                 */
                Route::get('dashboard/purchases', 'PurchaseController@index')
                    ->name('api.dashboard.purchases.index');

//                /**
//                 * Making payment for user purchases
//                 */
//                Route::patch('dashboard/purchases/{id}/make/payment', 'PurchaseController@makePayment')
//                    ->name('api.dashboard.purchases.make.payment');

//                /**
//                 * Canceling user purchase
//                 */
//                Route::patch('dashboard/purchases/{id}/cancel/purchase', 'PurchaseController@cancelPurchase')
//                    ->name('api.dashboard.purchases.cancel.purchase');

                /**
                 * Reschedule user purchase
                 */
                Route::patch('dashboard/purchases/{id}/reschedule', 'PurchaseController@reschedule')
                    ->name('api.dashboard.purchases.reschedule');

                /**
                 * Accepting user purchase reschedule request
                 */
                Route::patch('dashboard/purchases/{id}/reschedule/request/accept', 'PurchaseController@acceptRescheduleRequest')
                    ->name('api.dashboard.purchases.reschedule.request.accept');

                /**
                 * Declining user purchase reschedule request
                 */
                Route::patch('dashboard/purchases/{id}/reschedule/request/decline', 'PurchaseController@declineRescheduleRequest')
                    ->name('api.dashboard.purchases.reschedule.request.decline');

                /**
                 * Canceling user purchase reschedule request
                 */
                Route::patch('dashboard/purchases/{id}/reschedule/request/cancel', 'PurchaseController@cancelRescheduleRequest')
                    ->name('api.dashboard.purchases.reschedule.request.cancel');

                /**
                 * Canceling user purchase with refund
                 */
                Route::patch('dashboard/purchases/{id}/cancel/order', 'PurchaseController@cancelOrder')
                    ->name('api.dashboard.purchases.cancel.order');

                /**
                 * Marking user purchase as finished
                 */
                Route::patch('dashboard/purchases/{id}/mark/finished', 'PurchaseController@markAsFinished')
                    ->name('api.dashboard.purchases.mark.finished');

//                /**
//                 * Opening a dispute over user purchase
//                 */
//                Route::patch('dashboard/purchases/{id}/open/dispute', 'PurchaseController@openDispute')
//                    ->name('api.dashboard.purchases.open.dispute');

                /**
                 * Accepting user purchase finish request
                 */
                Route::patch('dashboard/purchases/{id}/finish/request/accept', 'PurchaseController@acceptFinishRequest')
                    ->name('api.dashboard.purchases.finish.request.accept');

                /**
                 * Declining user purchase finish request
                 */
                Route::patch('dashboard/purchases/{id}/finish/request/decline', 'PurchaseController@declineFinishRequest')
                    ->name('api.dashboard.purchases.finish.request.decline');
            });

            /**
             * Sale namespace
             */
            Route::group(['namespace' => 'Sale'], function () {

                /**
                 * Getting user sales
                 */
                Route::get('dashboard/sales', 'SaleController@index')
                    ->name('api.dashboard.sales.index');

                /**
                 * Accepting user sale
                 */
                Route::patch('dashboard/sales/{id}/accept/order', 'SaleController@acceptOrder')
                    ->name('api.dashboard.sales.accept.order');

                /**
                 * Declining user sale
                 */
                Route::patch('dashboard/sales/{id}/decline/order', 'SaleController@declineOrder')
                    ->name('api.dashboard.sales.decline.order');

                /**
                 * Rescheduling user sale
                 */
                Route::patch('dashboard/sales/{id}/reschedule', 'SaleController@reschedule')
                    ->name('api.dashboard.sales.reschedule');

                /**
                 * Accepting user sale reschedule request
                 */
                Route::patch('dashboard/sales/{id}/reschedule/request/accept', 'SaleController@acceptRescheduleRequest')
                    ->name('api.dashboard.sales.reschedule.request.accept');

                /**
                 * Declining user sale reschedule request
                 */
                Route::patch('dashboard/sales/{id}/reschedule/request/decline', 'SaleController@declineRescheduleRequest')
                    ->name('api.dashboard.sales.reschedule.request.decline');

                /**
                 * Canceling user sale reschedule request
                 */
                Route::patch('dashboard/sales/{id}/reschedule/request/cancel', 'SaleController@cancelRescheduleRequest')
                    ->name('api.dashboard.sales.reschedule.request.cancel');

                /**
                 * Marking user sale as finished
                 */
                Route::patch('dashboard/sales/{id}/mark/finished', 'SaleController@markAsFinished')
                    ->name('api.dashboard.sales.mark.finished');

//                /**
//                 * Opening a dispute over user sale
//                 */
//                Route::patch('dashboard/sales/{id}/open/dispute', 'SaleController@openDispute')
//                    ->name('api.dashboard.sales.open.dispute');

                /**
                 * Canceling user sales finish request
                 */
                Route::patch('dashboard/sales/{id}/finish/request/cancel', 'SaleController@cancelFinishRequest')
                    ->name('api.dashboard.sales.finish.request.cancel');
            });

            /**
             * Wallet namespace
             */
            Route::group(['namespace' => 'Wallet'], function () {

                /**
                 * Getting wallet page
                 */
                Route::get('dashboard/wallet', 'WalletController@index')
                    ->name('api.dashboard.wallet.index');

                /**
                 * Add funds receipts CRUD
                 */
                Route::resource('dashboard/wallet/add/funds/receipts', 'AddFundsController')
                    ->parameters([
                        'receipts' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.dashboard.wallet.add.funds.receipts.index',
                        'store' => 'api.dashboard.wallet.add.funds.receipts.store'
                    ]);

                /**
                 * Executing add funds receipt payment
                 */
                Route::post('dashboard/wallet/add/funds/receipts/{id}/payment/execute', 'AddFundsController@executePayment')
                    ->name('api.dashboard.wallet.add.funds.receipts.payment.execute');

                /**
                 * Canceling add funds receipt payment
                 */
                Route::post('dashboard/wallet/add/funds/receipts/{id}/payment/cancel', 'AddFundsController@cancelPayment')
                    ->name('api.dashboard.wallet.add.funds.receipts.payment.cancel');

                /**
                 * Withdrawal CRUD
                 */
                Route::resource('dashboard/wallet/withdrawals', 'WithdrawalController')
                    ->parameters([
                        'withdrawals' => 'id'
                    ])
                    ->except([
                        'show',
                        'create',
                        'edit',
                        'update',
                        'destroy'
                    ])
                    ->names([
                        'index' => 'api.dashboard.wallet.withdrawals.index',
                        'store' => 'api.dashboard.wallet.withdrawals.store'
                    ]);

                /**
                 * Closing declined withdrawal request
                 */
                Route::patch('dashboard/wallet/withdrawal/request/{id}/close', 'WithdrawalController@closeRequest')
                    ->name('api.dashboard.wallet.withdrawal.request.close');

                /**
                 * Canceling withdrawal request
                 */
                Route::delete('dashboard/wallet/withdrawal/request/{id}/cancel', 'WithdrawalController@cancelRequest')
                    ->name('api.dashboard.wallet.withdrawal.request.cancel');
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
                     * Getting finance buyer add funds receipts
                     */
                    Route::get('dashboard/finance/buyer/add/funds/receipts', 'AddFundsReceiptController@index')
                        ->name('api.dashboard.finance.buyer.add.funds.receipts.index');

                    /**
                     * Getting finance buyer add funds receipt pdf page
                     */
                    Route::get('dashboard/finance/buyer/add/funds/receipts/{id}/pdf', 'AddFundsReceiptController@viewPdf')
                        ->name('api.dashboard.finance.buyer.add.funds.receipts.pdf.view');

                    /**
                     * Downloading finance buyer add funds receipt pdf
                     */
                    Route::get('dashboard/finance/buyer/add/funds/receipts/{id}/pdf/download', 'AddFundsReceiptController@downloadPdf')
                        ->name('api.dashboard.finance.buyer.add.funds.receipts.pdf.download');

                    /**
                     * Getting finance buyer invoices
                     */
                    Route::get('dashboard/finance/buyer/invoices', 'InvoiceForBuyerController@index')
                        ->name('api.dashboard.finance.buyer.invoices.index');

                    /**
                     * Getting finance buyer invoice pdf page
                     */
                    Route::get('dashboard/finance/buyer/invoices/{id}/pdf', 'InvoiceForBuyerController@viewPdf')
                        ->name('api.dashboard.finance.buyer.invoices.pdf.view');

                    /**
                     * Downloading finance buyer invoice pdf
                     */
                    Route::get('dashboard/finance/buyer/invoices/{id}/pdf/download', 'InvoiceForBuyerController@downloadPdf')
                        ->name('api.dashboard.finance.buyer.invoices.pdf.download');

                    /**
                     * Getting finance buyer tip invoices
                     */
                    Route::get('dashboard/finance/buyer/tip/invoices', 'TipInvoiceForBuyerController@index')
                        ->name('api.dashboard.finance.buyer.tip.invoices.index');

                    /**
                     * Getting finance buyer tip invoice pdf page
                     */
                    Route::get('dashboard/finance/buyer/tip/invoices/{id}/pdf', 'TipInvoiceForBuyerController@viewPdf')
                        ->name('api.dashboard.finance.buyer.tip.invoices.pdf.view');

                    /**
                     * Downloading finance buyer tip invoice pdf
                     */
                    Route::get('dashboard/finance/buyer/tip/invoices/{id}/pdf/download', 'TipInvoiceForBuyerController@downloadPdf')
                        ->name('api.dashboard.finance.buyer.tip.invoices.pdf.download');

                });

                /**
                 * Seller namespace
                 */
                Route::group(['namespace' => 'Seller'], function () {

                    /**
                     * Getting finance seller invoices
                     */
                    Route::get('dashboard/finance/seller/invoices', 'InvoiceForSellerController@index')
                        ->name('api.dashboard.finance.seller.invoices.index');

                    /**
                     * Getting finance seller invoice pdf page
                     */
                    Route::get('dashboard/finance/seller/invoices/{id}/pdf', 'InvoiceForSellerController@viewPdf')
                        ->name('api.dashboard.finance.seller.invoices.pdf.view');

                    /**
                     * Downloading finance seller invoice pdf
                     */
                    Route::get('dashboard/finance/seller/invoices/{id}/pdf/download', 'InvoiceForSellerController@downloadPdf')
                        ->name('api.dashboard.finance.seller.invoices.pdf.download');

                    /**
                     * Getting finance seller tip invoices
                     */
                    Route::get('dashboard/finance/seller/tip/invoices', 'TipInvoiceForSellerController@index')
                        ->name('api.dashboard.finance.seller.tip.invoices.index');

                    /**
                     * Getting finance seller tip invoice pdf page
                     */
                    Route::get('dashboard/finance/seller/tip/invoices/{id}/pdf', 'TipInvoiceForSellerController@viewPdf')
                        ->name('api.dashboard.finance.seller.tip.invoices.pdf.view');

                    /**
                     * Downloading finance seller tip invoice pdf
                     */
                    Route::get('dashboard/finance/seller/tip/invoices/{id}/pdf/download', 'TipInvoiceForSellerController@downloadPdf')
                        ->name('api.dashboard.finance.seller.tip.invoices.pdf.download');

                    /**
                     * Getting finance seller withdrawal receipts
                     */
                    Route::get('dashboard/finance/seller/withdrawal/receipts', 'WithdrawalReceiptController@index')
                        ->name('api.dashboard.finance.seller.withdrawal.receipts.index');

                    /**
                     * Getting finance seller withdrawal receipt pdf page
                     */
                    Route::get('dashboard/finance/seller/withdrawal/receipts/{id}/pdf', 'WithdrawalReceiptController@viewPdf')
                        ->name('api.dashboard.finance.seller.withdrawal.receipts.pdf.view');

                    /**
                     * Downloading finance seller withdrawal receipt pdf
                     */
                    Route::get('dashboard/finance/seller/withdrawal/receipts/{id}/pdf/download', 'WithdrawalReceiptController@downloadPdf')
                        ->name('api.dashboard.finance.seller.withdrawal.receipts.pdf.download');
                });
            });
        });

        /**
         * Home namespace
         */
        Route::group(['namespace' => 'Home', 'prefix' => 'home'], function () {

            /**
             * Getting vybes ordered by me with pagination
             */
            Route::get('vybes/ordered', 'VybeController@getOrderedByAuthUserVybes')
                ->name('api.home.vybes.ordered');

            /**
             * Getting vybes by following users with pagination
             */
            Route::get('vybes/by/following/users', 'VybeController@getVybesByFollowingUsers')
                ->name('api.home.vybes.by.following.users');

            /**
             * Getting not discovered vybes
             */
            Route::get('vybes/discover/new', 'VybeController@getVybesNotDiscovered')
                ->name('api.home.vybes.discover.new');

            /**
             * Getting vybes recommended for me
             */
            Route::get('vybes/recommended/for/me', 'VybeController@getVybesRecommendedForMe')
                ->name('api.home.vybes.recommended.for.me');
        });

        /**
         * Navbar namespace
         */
        Route::group(['namespace' => 'Navbar'], function () {

            /**
             * Provides user navbar getting data
             */
            Route::get('navbar/user', 'NavbarController@index')
                ->name('api.navbar.user');

            /**
             * Provides user language updating
             */
            Route::patch('navbar/language/{id}', 'NavbarController@updateLanguage')
                ->name('api.navbar.language.update');

            /**
             * Provides user currency updating
             */
            Route::patch('navbar/currency/{id}', 'NavbarController@updateCurrency')
                ->name('api.navbar.currency.update');

            /**
             * Provides user state status updating
             */
            Route::patch('navbar/state/status/{id}', 'NavbarController@updateStateStatus')
                ->name('api.navbar.state.status.update');

            /**
             * Provides user timezone updating
             */
            Route::patch('navbar/timezone/{id}', 'NavbarController@updateTimezone')
                ->name('api.navbar.timezone.update');

            /**
             * Provides user theme updating
             */
            Route::patch('navbar/theme/{id}', 'NavbarController@updateTheme')
                ->name('api.navbar.theme.update');
        });

        /**
         * PersonalityTraits namespace
         */
        Route::group(['namespace' => 'PersonalityTrait'], function () {

            /**
             * Storing personality trait vote
             */
            Route::post('personality/trait/{id}/user/{userId}/vote', 'PersonalityTraitVoteController@store')
                ->name('api.personality.trait.user.vote.store');

            /**
             * Destroying personality trait vote
             */
            Route::delete('personality/trait/{id}/user/{userId}/vote', 'PersonalityTraitVoteController@destroy')
                ->name('api.personality.trait.user.vote.destroy');
        });

        /**
         * Place namespace
         */
        Route::group(['namespace' => 'Place'], function () {

            /**
             * Getting google places city autocomplete
             */
            Route::get('places/autocomplete/city', 'PlaceController@autocompleteCity')
                ->name('api.places.autocomplete.city');
        });

        /**
         * Profile namespace
         */
        Route::group(['namespace' => 'Profile'], function () {

            /**
             * Voting to user personality trait
             */
            Route::post('profiles/{username}/personality/trait/{id}/vote', 'HomeController@personalityTraitVote')
                ->name('api.profiles.personality.trait.vote');

            /**
             * Like / unlike profile user image
             */
            Route::patch('profiles/{username}/user/image/{id}/like', 'HomeController@likeUserImage')
                ->name('api.profiles.user.image.like');

            /**
             * Like / unlike profile user video
             */
            Route::patch('profiles/{username}/user/video/{id}/like', 'HomeController@likeUserVideo')
                ->name('api.profiles.user.video.like');

            /**
             * Getting profile private vybe
             */
            Route::post('profiles/{username}/vybes/{id}', 'VybeController@show')
                ->name('api.profiles.vybes.show.private');
        });

        /**
         * Setting namespace
         */
        Route::group(['namespace' => 'Setting'], function () {

            /**
             * Provides getting blocked users
             */
            Route::get('settings/account', 'AccountSettingController@index')
                ->name('api.settings.account.index');

            /**
             * Provides getting blocked users
             */
            Route::get('settings/account/blocked/users', 'AccountSettingController@getBlockedUsers')
                ->name('api.settings.account.blocked.users');

            /**
             * Provides getting user fast order page
             */
            Route::get('settings/account/fast/order/page', 'AccountSettingController@getFastOrderPage')
                ->name('api.settings.account.fast.order.page');

            /**
             * Provides account deactivation
             */
            Route::post('settings/account/deactivation', 'AccountSettingController@deactivation')
                ->name('api.settings.account.deactivation.store');

            /**
             * Provides closing declined account deactivation request
             */
            Route::patch('settings/account/deactivation/request/close', 'AccountSettingController@closeDeactivation')
                ->name('api.settings.account.deactivation.request.close');

            /**
             * Provides canceling account deactivation
             */
            Route::delete('settings/account/deactivation', 'AccountSettingController@cancelDeactivation')
                ->name('api.settings.account.deactivation.destroy');

            /**
             * Provides account deletion
             */
            Route::post('settings/account/deletion', 'AccountSettingController@deletion')
                ->name('api.settings.account.deletion.store');

            /**
             * Provides closing declined account deletion request
             */
            Route::patch('settings/account/deletion/request/close', 'AccountSettingController@closeDeletion')
                ->name('api.settings.account.deletion.request.close');

            /**
             * Provides cancelling account deletion
             */
            Route::delete('settings/account/deletion', 'AccountSettingController@cancelDeletion')
                ->name('api.settings.account.deletion.destroy');

            /**
             * Provides account unsuspend
             */
            Route::post('settings/account/unsuspend', 'AccountSettingController@unsuspend')
                ->name('api.settings.account.unsuspend.store');

            /**
             * Provides closing declined account unsuspend request
             */
            Route::patch('settings/account/unsuspend/request/close', 'AccountSettingController@closeUnsuspend')
                ->name('api.settings.account.unsuspend.request.close');

            /**
             * Provides canceling account unsuspend
             */
            Route::delete('settings/account/unsuspend', 'AccountSettingController@cancelUnsuspend')
                ->name('api.settings.account.unsuspend.destroy');

            /**
             * Provides account enable fast order
             */
            Route::patch('settings/account/fast/order/page', 'AccountSettingController@changeFastOrder')
                ->name('api.settings.fast.order.page');

            /**
             * Provides account email changing
             */
            Route::patch('settings/account/email', 'AccountSettingController@changeEmail')
                ->name('api.settings.email');

            /**
             * Provides account email resubmit
             */
            Route::patch('settings/account/email/resubmit', 'AccountSettingController@resubmitEmail')
                ->name('api.settings.email.resubmit');

            /**
             * Provides account password changing
             */
            Route::patch('settings/account/password', 'AccountSettingController@changePassword')
                ->name('api.settings.password');

            /**
             * Provides account language changing
             */
            Route::patch('settings/account/platform/language/{id}', 'AccountSettingController@changeLanguage')
                ->name('api.settings.account.platform.language');

            /**
             * Provides account currency changing
             */
            Route::patch('settings/account/platform/currency/{id}', 'AccountSettingController@changeCurrency')
                ->name('api.settings.account.platform.currency');

            /**
             * Provides account timezone changing
             */
            Route::patch('settings/account/platform/timezone', 'AccountSettingController@changeTimezone')
                ->name('api.settings.account.platform.timezone');

            /**
             * Providing user account reactivation
             */
            Route::patch('settings/account/reactivate', 'AccountSettingController@reactivateAccount')
                ->name('api.settings.account.reactivate');

            /**
             * Providing user account restore
             */
            Route::patch('settings/account/restore', 'AccountSettingController@restoreAccount')
                ->name('api.settings.account.restore');

            /**
             * Provides getting id verification
             */
            Route::get('settings/id/verification', 'UserIdVerificationController@index')
                ->name('api.settings.id.verification.index');

            /**
             * Provides updating id verification
             */
            Route::patch('settings/id/verification', 'UserIdVerificationController@update')
                ->name('api.settings.id.verification.update');

            /**
             * Provides closing declined id verification request
             */
            Route::patch('settings/id/verification/request/close', 'UserIdVerificationController@close')
                ->name('api.settings.id.verification.request.close');

            /**
             * Provides destroying id verification
             */
            Route::delete('settings/id/verification', 'UserIdVerificationController@destroy')
                ->name('api.settings.id.verification.destroy');

            /**
             * Provides getting billing
             */
            Route::get('settings/billing', 'BillingController@index')
                ->name('api.settings.billing.index');

            /**
             * Provides updating billing
             */
            Route::patch('settings/billing', 'BillingController@update')
                ->name('api.settings.billing.request');

            /**
             * Provides closing declined billing change request
             */
            Route::patch('settings/billing/change/request/close', 'BillingController@close')
                ->name('api.settings.billing.change.request.close');

            /**
             * Provides deleting pending billing change request
             */
            Route::delete('settings/billing', 'BillingController@destroy')
                ->name('api.settings.billing.destroy');

            /**
             * Payout methods CRUD
             */
            Route::resource('settings/payout/methods', 'PayoutMethodController')
                ->parameters([
                    'methods' => 'id'
                ])
                ->except([
                    'show',
                    'create',
                    'edit'
                ])
                ->names([
                    'index'   => 'api.settings.payout.methods.index',
                    'store'   => 'api.settings.payout.methods.store',
                    'update'  => 'api.settings.payout.methods.update',
                    'destroy' => 'api.settings.payout.methods.destroy'
                ]);

            /**
             * Provides cancelling payout method request
             */
            Route::delete('settings/payout/methods/{id}/request/cancel', 'PayoutMethodController@cancelRequest')
                ->name('api.settings.payout.methods.request/cancel');

            /**
             * Provides getting notification settings
             */
            Route::get('settings/notification', 'NotificationSettingController@index')
                ->name('api.settings.notification.index');

            /**
             * Provides saving notification settings
             */
            Route::post('settings/notification/save', 'NotificationSettingController@saveChanges')
                ->name('api.settings.notification.save');
        });

        /**
         * Tip namespace
         */
        Route::group(['namespace' => 'Tip'], function () {

            /**
             * Tip CRUD
             */
            Route::resource('tips', 'TipController')
                ->parameters([
                    'tips' => 'id'
                ])
                ->except([
                    'show',
                    'create',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'index' => 'api.tips.index',
                    'store' => 'api.tips.store'
                ]);

            /**
             * Provides tip payment executing
             */
            Route::post('tips/{id}/payment/execute', 'TipController@executePayment');

            /**
             * Provides tip payment canceling
             */
            Route::post('tips/{id}/payment/cancel', 'TipController@cancelPayment');
        });

        /**
         * User namespace
         */
        Route::group(['namespace' => 'User'], function () {

            /**
             * Users CRUD
             */
            Route::resource('users', 'UserController')
                ->parameters([
                    'users' => 'id'
                ])
                ->except([
                    'create',
                    'store',
                    'edit',
                    'update'
                ])
                ->names([
                    'index'   => 'api.users.index',
                    'show'    => 'api.users.show',
                    'destroy' => 'api.users.destroy'
                ]);

            /**
             * Getting user subscriptions
             */
            Route::get('users/{id}/subscriptions', 'UserController@getUserSubscriptions')
                ->name('api.users.subscriptions');

            /**
             * Getting user subscribers
             */
            Route::get('users/{id}/subscribers', 'UserController@getUserSubscribers')
                ->name('api.users.subscribers');

            /**
             * Storing user visit
             */
            Route::post('users/{id}/visit', 'UserController@storeVisit')
                ->name('api.users.visit');

            /**
             * User referral CRUD
             */
            Route::resource('user/referrals', 'UserReferralController')
                ->parameters([
                    'referrals' => 'id'
                ])
                ->except([
                    'create',
                    'edit'
                ])
                ->names([
                    'index'   => 'api.user.referrals.index',
                    'show'    => 'api.user.referrals.show',
                    'store'   => 'api.user.referrals.store',
                    'update'  => 'api.user.referrals.update',
                    'destroy' => 'api.user.referrals.destroy'
                ]);

            /**
             * User reports CRUD
             */
            Route::resource('user/reports', 'UserReportController')
                ->parameters([
                    'reports' => 'id'
                ])
                ->except([
                    'create',
                    'edit'
                ])
                ->names([
                    'index'   => 'api.user.reports.index',
                    'show'    => 'api.user.reports.show',
                    'store'   => 'api.user.reports.store',
                    'update'  => 'api.user.reports.update',
                    'destroy' => 'api.user.reports.destroy'
                ]);
        });

        /**
         * Vybe namespace
         */
        Route::group(['namespace' => 'Vybe'], function () {

            /**
             * Storing vybe changes
             */
            Route::post('vybes/step/changes', 'VybeStepController@storeChanges')
                ->name('api.vybes.step.store.changes');

            /**
             * Storing vybe next
             */
            Route::post('vybes/step/next', 'VybeStepController@storeNext')
                ->name('api.vybes.step.store.next');

            /**
             * Generating vybe step routes
             */
            foreach (VybeStepList::getOnlyEditSteps() as $vybeStep) {

                /**
                 * Updating vybe step changes routes
                 */
                Route::patch('vybes/{id}/step/' . $vybeStep->id . '/changes', 'VybeStepController@update' . ucfirst($vybeStep->code) . 'StepChanges')
                    ->name('api.vybes.update.step.' . $vybeStep->id . '.changes');

                /**
                 * Updating vybe step next routes
                 */
                Route::patch('vybes/{id}/step/' . $vybeStep->id . '/next', 'VybeStepController@update' . ucfirst($vybeStep->code) . 'StepNext')
                    ->name('api.vybes.update.step.' . $vybeStep->id . '.next');
            }

            /**
             * Vybes CRUD
             */
            Route::resource('vybes', 'VybeController')
                ->parameters([
                    'vybes' => 'id'
                ])
                ->except([
                    'index',
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ])
                ->names([
                    'show' => 'api.vybes.show'
                ]);

            /**
             * Getting vybe form data
             */
            Route::get('vybes/form/data', 'VybeController@getFormData')
                ->name('api.vybes.form.data');

            /**
             * Updating vybe with draft request
             */
            Route::patch('vybes/{id}/draft/to/draft', 'VybeController@update')
                ->name('api.vybes.draft.to.draft');

            /**
             * Updating vybe with suspended to suspended request
             */
            Route::patch('vybes/{id}/suspended/to/suspended', 'VybeController@rejectSuspended')
                ->name('api.vybes.suspended.to.suspended');

            /**
             * Updating vybe with publish request
             */
            Route::patch('vybes/{id}/draft/to/published', 'VybePublishRequestController@update')
                ->name('api.vybes.draft.to.published');

            /**
             * Closing declined vybe publish request
             */
            Route::patch('vybes/{id}/publish/request/close', 'VybePublishRequestController@close')
                ->name('api.vybe.publish.request.close');

            /**
             * Canceling vybe publish request
             */
            Route::delete('vybes/{id}/publish/request/cancel', 'VybePublishRequestController@cancel')
                ->name('api.vybes.publish.request.cancel');

            /**
             * Updating vybe with published to published request
             */
            Route::patch('vybes/{id}/published/to/published', 'VybeChangeRequestController@update')
                ->name('api.vybes.published.to.published');

            /**
             * Updating vybe with published to paused request
             */
            Route::patch('vybes/{id}/published/to/paused', 'VybeChangeRequestController@update')
                ->name('api.vybes.published.to.paused');

            /**
             * Updating vybe with paused to published request
             */
            Route::patch('vybes/{id}/paused/to/published', 'VybeChangeRequestController@update')
                ->name('api.vybes.paused.to.published');

            /**
             * Updating vybe with paused to paused request
             */
            Route::patch('vybes/{id}/paused/to/paused', 'VybeChangeRequestController@update')
                ->name('api.vybes.paused.to.paused');

            /**
             * Closing declined vybe change request
             */
            Route::patch('vybes/{id}/change/request/close', 'VybeChangeRequestController@close')
                ->name('api.vybe.change.request.close');

            /**
             * Canceling vybe change request
             */
            Route::delete('vybes/{id}/change/request/cancel', 'VybeChangeRequestController@cancel')
                ->name('api.vybes.change.request.cancel');

            /**
             * Updating vybe with published to draft request
             */
            Route::patch('vybes/{id}/published/to/draft', 'VybeUnpublishRequestController@update')
                ->name('api.vybes.published.to.draft');

            /**
             * Updating vybe with paused to draft request
             */
            Route::patch('vybes/{id}/paused/to/draft', 'VybeUnpublishRequestController@update')
                ->name('api.vybes.paused.to.draft');

            /**
             * Closing declined vybe unpublish request
             */
            Route::patch('vybes/{id}/unpublish/request/close', 'VybeUnpublishRequestController@close')
                ->name('api.vybe.unpublish.request.close');

            /**
             * Canceling vybe unpublish request
             */
            Route::delete('vybes/{id}/unpublish/request/cancel', 'VybeUnpublishRequestController@cancel')
                ->name('api.vybes.unpublish.request.cancel');

            /**
             * Updating vybe with suspended to draft request
             */
            Route::patch('vybes/{id}/suspended/to/draft', 'VybeUnsuspendRequestController@update')
                ->name('api.vybes.suspended.to.draft');

            /**
             * Closing declined vybe unsuspend request
             */
            Route::patch('vybes/{id}/unsuspend/request/close', 'VybeUnsuspendRequestController@close')
                ->name('api.vybe.unsuspend.request.close');

            /**
             * Canceling vybe unsuspend request
             */
            Route::delete('vybes/{id}/unsuspend/request/cancel', 'VybeUnsuspendRequestController@cancel')
                ->name('api.vybes.unsuspend.request.cancel');

            /**
             * Updating vybe with draft to deleted request
             */
            Route::patch('vybes/{id}/draft/to/deleted', 'VybeDeletionRequestController@update')
                ->name('api.vybes.draft.to.deleted');

            /**
             * Updating vybe with published to delete request
             */
            Route::patch('vybes/{id}/published/to/deleted', 'VybeDeletionRequestController@update')
                ->name('api.vybes.published.to.deleted');

            /**
             * Updating vybe with paused to delete request
             */
            Route::patch('vybes/{id}/paused/to/deleted', 'VybeDeletionRequestController@update')
                ->name('api.vybes.paused.to.deleted');

            /**
             * Updating vybe with suspended to deleted
             */
            Route::patch('vybes/{id}/suspended/to/deleted', 'VybeDeletionRequestController@update')
                ->name('api.vybes.suspended.to.deleted');

            /**
             * Closing declined vybe deletion request
             */
            Route::patch('vybes/{id}/deletion/request/close', 'VybeDeletionRequestController@close')
                ->name('api.vybe.deletion.request.close');

            /**
             * Canceling vybe deletion request
             */
            Route::delete('vybes/{id}/deletion/request/cancel', 'VybeDeletionRequestController@cancel')
                ->name('api.vybes.deletion.request.cancel');

            /**
             * Vybe rating votes CRUD
             */
            Route::resource('vybe/rating/votes', 'VybeRatingVoteController')
                ->parameters([
                    'votes' => 'id'
                ])
                ->except([
                    'create',
                    'edit'
                ])
                ->names([
                    'index'   => 'api.vybe.rating.votes.index',
                    'show'    => 'api.vybe.rating.votes.show',
                    'store'   => 'api.vybe.rating.votes.store',
                    'update'  => 'api.vybe.rating.votes.update',
                    'destroy' => 'api.vybe.rating.votes.destroy'
                ]);
        });
    });
});
