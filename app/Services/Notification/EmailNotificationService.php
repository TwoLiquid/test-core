<?php

namespace App\Services\Notification;

use App\Jobs\Notification\Email\Account\AccountEmailResendJob;
use App\Jobs\Notification\Email\Account\AccountEmailVerificationJob;
use App\Jobs\Notification\Email\Account\AccountPasswordChangedJob;
use App\Jobs\Notification\Email\Account\AccountPasswordResetJob;
use App\Jobs\Notification\Email\Account\AccountProfileIncompleteJob;
use App\Jobs\Notification\Email\AddFunds\AddFundsSuccessfulJob;
use App\Jobs\Notification\Email\AffiliateCommission\AffiliateCommissionPurchaseJob;
use App\Jobs\Notification\Email\AffiliateCommission\AffiliateCommissionSaleJob;
use App\Jobs\Notification\Email\AffiliateCommission\AffiliateCommissionUserJob;
use App\Jobs\Notification\Email\Chargeback\ChargebackDisputeOpenedJob;
use App\Jobs\Notification\Email\Chat\ChatMessageUnreadJob;
use App\Jobs\Notification\Email\Follower\FollowerEventAvailableJob;
use App\Jobs\Notification\Email\Follower\FollowerNewJob;
use App\Jobs\Notification\Email\Follower\FollowerVybeAvailableJob;
use App\Jobs\Notification\Email\Purchase\PurchaseAcceptedJob;
use App\Jobs\Notification\Email\Purchase\PurchaseDeclinedJob;
use App\Jobs\Notification\Email\Purchase\PurchaseFinishPendingJob;
use App\Jobs\Notification\Email\Purchase\PurchaseOrderCompleteJob;
use App\Jobs\Notification\Email\Purchase\PurchaseOrderExpiredJob;
use App\Jobs\Notification\Email\Purchase\PurchaseOrderTippedJob;
use App\Jobs\Notification\Email\Purchase\PurchasePaymentSuccessfulJob;
use App\Jobs\Notification\Email\Purchase\PurchaseSuccessfulJob;
use App\Jobs\Notification\Email\Purchase\PurchaseVybeCanceledJob;
use App\Jobs\Notification\Email\Purchase\PurchaseVybeCompleteJob;
use App\Jobs\Notification\Email\Purchase\PurchaseVybeStartingJob;
use App\Jobs\Notification\Email\Refund\RefundReceivedJob;
use App\Jobs\Notification\Email\Refund\RefundVybeReceivedJob;
use App\Jobs\Notification\Email\Reschedule\RescheduleRequestCanceledJob;
use App\Jobs\Notification\Email\Reschedule\RescheduleRequestDeclinedJob;
use App\Jobs\Notification\Email\Reschedule\RescheduleRequestReceivedJob;
use App\Jobs\Notification\Email\Reschedule\RescheduleRequestSuccessfulJob;
use App\Jobs\Notification\Email\Review\ReviewProfileNewJob;
use App\Jobs\Notification\Email\Review\ReviewProfilePendingJob;
use App\Jobs\Notification\Email\Review\ReviewVybeNewJob;
use App\Jobs\Notification\Email\Review\ReviewVybePendingJob;
use App\Jobs\Notification\Email\Sale\SaleActionRequiredJob;
use App\Jobs\Notification\Email\Sale\SaleCanceledJob;
use App\Jobs\Notification\Email\Sale\SaleFinishDeclinedJob;
use App\Jobs\Notification\Email\Sale\SaleFinishedJob;
use App\Jobs\Notification\Email\Sale\SaleOrderNewJob;
use App\Jobs\Notification\Email\Sale\SaleOrderTipJob;
use App\Jobs\Notification\Email\Sale\SaleVybeSoldJob;
use App\Jobs\Notification\Email\Sale\SaleVybeStartingJob;
use App\Jobs\Notification\Email\Suspension\SuspensionAccountJob;
use App\Jobs\Notification\Email\Ticket\Dispute\DisputeTicketOpenedJob;
use App\Jobs\Notification\Email\Ticket\Dispute\DisputeTicketResponseJob;
use App\Jobs\Notification\Email\Ticket\Dispute\DisputeTicketSettledJob;
use App\Jobs\Notification\Email\Ticket\TicketCreatedJob;
use App\Jobs\Notification\Email\Ticket\TicketResponseJob;
use App\Jobs\Notification\Email\User\Request\BillingChange\BillingChangeAcceptedJob;
use App\Jobs\Notification\Email\User\Request\BillingChange\BillingChangeDeclinedJob;
use App\Jobs\Notification\Email\User\Request\Deactivation\DeactivationRequestAcceptedJob;
use App\Jobs\Notification\Email\User\Request\Deactivation\DeactivationRequestDeclinedJob;
use App\Jobs\Notification\Email\User\Request\Deletion\DeletionRequestAcceptedJob;
use App\Jobs\Notification\Email\User\Request\Deletion\DeletionRequestDeclinedJob;
use App\Jobs\Notification\Email\User\Request\IdVerification\IdVerificationAcceptedJob;
use App\Jobs\Notification\Email\User\Request\IdVerification\IdVerificationDeclinedJob;
use App\Jobs\Notification\Email\User\Request\Unsuspension\UnsuspensionRequestAcceptedJob;
use App\Jobs\Notification\Email\User\Request\Unsuspension\UnsuspensionRequestDeclinedJob;
use App\Jobs\Notification\Email\User\Request\Unsuspension\UnsuspensionRequestSubmittedJob;
use App\Jobs\Notification\Email\User\UserProfileApprovedJob;
use App\Jobs\Notification\Email\User\UserProfileDeclinedJob;
use App\Jobs\Notification\Email\User\UserWelcomeJob;
use App\Jobs\Notification\Email\Vybe\Request\Change\VybeChangeAcceptedJob;
use App\Jobs\Notification\Email\Vybe\Request\Change\VybeChangeDeclinedJob;
use App\Jobs\Notification\Email\Vybe\Request\Deletion\VybeDeletionAcceptedJob;
use App\Jobs\Notification\Email\Vybe\Request\Deletion\VybeDeletionDeclinedJob;
use App\Jobs\Notification\Email\Vybe\Request\Publish\VybePublishAcceptedJob;
use App\Jobs\Notification\Email\Vybe\Request\Publish\VybePublishDeclinedJob;
use App\Jobs\Notification\Email\Vybe\Request\Unpublish\VybeUnpublishAcceptedJob;
use App\Jobs\Notification\Email\Vybe\Request\Unpublish\VybeUnpublishDeclinedJob;
use App\Jobs\Notification\Email\Vybe\Request\Unsuspension\VybeUnsuspensionAcceptedJob;
use App\Jobs\Notification\Email\Vybe\Request\Unsuspension\VybeUnsuspensionDeclinedJob;
use App\Jobs\Notification\Email\Vybe\VybeActionRequiredJob;
use App\Jobs\Notification\Email\Vybe\VybeSuspendedJob;
use App\Jobs\Notification\Email\Withdrawal\WithdrawalMethodAcceptedJob;
use App\Jobs\Notification\Email\Withdrawal\WithdrawalMethodDeclinedJob;
use App\Jobs\Notification\Email\Withdrawal\WithdrawalRequestAcceptedJob;
use App\Jobs\Notification\Email\Withdrawal\WithdrawalRequestDeclinedJob;
use App\Lists\Language\LanguageList;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Services\Notification\Interfaces\EmailNotificationServiceInterface;
use Carbon\Carbon;

/**
 * Class EmailNotificationService
 *
 * @package App\Services\Notification
 */
class EmailNotificationService implements EmailNotificationServiceInterface
{
    //--------------------------------------------------------------------------
    // Users

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendUserWelcome(
        User $user
    ) : void
    {
        /**
         * Sending user register welcome email
         */
        UserWelcomeJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username'           => $user->username,
            'email'              => $user->email,
            'email_verify_token' => $user->email_verify_token,
            'email_verify_url'   => config('auth.email.verify.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendUserProfileApproved(
        User $user
    ) : void
    {
        /**
         * Sending user profile request approve email notification
         */
        UserProfileApprovedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendUserProfileDeclined(
        User $user
    ) : void
    {
        /**
         * Sending user profile request decline email notification
         */
        UserProfileDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Followers

    /**
     * @param User $user
     * @param User $follower
     *
     * @return void
     */
    public function sendFollowerNew(
        User $user,
        User $follower
    ) : void
    {
        FollowerNewJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $follower->username,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param User $follower
     *
     * @return void
     */
    public function sendFollowerVybeAvailable(
        User $user,
        User $follower
    ) : void
    {
        FollowerVybeAvailableJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $follower->username,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param User $follower
     *
     * @return void
     */
    public function sendFollowerEventAvailable(
        User $user,
        User $follower
    ) : void
    {
        FollowerEventAvailableJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $follower->username,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Vybes

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybePublishDeclined(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybePublishDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybePublishAccepted(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybePublishAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpublishDeclined(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeUnpublishDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpublishAccepted(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeUnpublishAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDeletionDeclined(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeDeletionDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDeletionAccepted(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeDeletionAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeSuspended(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeSuspendedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnsuspensionDeclined(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeUnsuspensionDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnsuspensionAccepted(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeUnsuspensionAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeChangeDeclined(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeChangeDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeChangeAccepted(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeChangeAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeActionRequired(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeActionRequiredJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Sales

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleVybeStarting(
        User $user,
        Vybe $vybe
    ) : void
    {
        SaleVybeStartingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleOrderNew(
        User $user,
        Vybe $vybe
    ) : void
    {
        SaleOrderNewJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Carbon $startDatetime
     *
     * @return void
     */
    public function sendSaleVybeSold(
        User $user,
        Vybe $vybe,
        Carbon $startDatetime
    ) : void
    {
        SaleVybeSoldJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username'       => $user->username,
            'title'          => $vybe->title,
            'start_datetime' => $startDatetime->format('m.d.Y H:i:s'),
            'url'            => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleCanceled(
        User $user,
        Vybe $vybe
    ) : void
    {
        SaleCanceledJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleFinished(
        User $user,
        Vybe $vybe
    ) : void
    {
        SaleFinishedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleFinishDeclined(
        User $user,
        Vybe $vybe
    ) : void
    {
        SaleFinishDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Sale $sale
     * @param Carbon $datetime
     *
     * @return void
     */
    public function sendSaleActionRequired(
        User $user,
        Vybe $vybe,
        Sale $sale,
        Carbon $datetime
    ) : void
    {
        SaleActionRequiredJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'sale_id'  => $sale->id,
            'datetime' => $datetime->format('H:i:s'),
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param float $amount
     *
     * @return void
     */
    public function sendSaleOrderTip(
        User $user,
        Vybe $vybe,
        float $amount
    ) : void
    {
        SaleOrderTipJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'amount'   => $amount,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Purchases

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseOrderExpired(
        User $user,
        Vybe $vybe
    ) : void
    {
        PurchaseOrderExpiredJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'title' => $vybe->title,
            'url'   => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseVybeStarting(
        User $user,
        Vybe $vybe
    ) : void
    {
        PurchaseVybeStartingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseVybeComplete(
        User $user,
        Vybe $vybe
    ) : void
    {
        PurchaseVybeCompleteJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseSuccessful(
        User $user,
        Vybe $vybe
    ) : void
    {
        PurchaseSuccessfulJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendPurchaseOrderComplete(
        User $user
    ) : void
    {
        PurchaseOrderCompleteJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendPurchasePaymentSuccessful(
        User $user
    ) : void
    {
        PurchasePaymentSuccessfulJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Carbon $startDatetime
     *
     * @return void
     */
    public function sendPurchaseAccepted(
        User $user,
        Vybe $vybe,
        Carbon $startDatetime
    ) : void
    {
        PurchaseAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username'       => $user->username,
            'title'          => $vybe->title,
            'start_datetime' => $startDatetime->format('d.m.Y H:i:s'),
            'url'            => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param float $amount
     *
     * @return void
     */
    public function sendPurchaseDeclined(
        User $user,
        Vybe $vybe,
        float $amount
    ) : void
    {
        PurchaseDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'amount'   => $amount,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Carbon $startDatetime
     * @param float $amount
     *
     * @return void
     */
    public function sendPurchaseVybeCanceled(
        User $user,
        Vybe $vybe,
        Carbon $startDatetime,
        float $amount
    ) : void
    {
        PurchaseVybeCanceledJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username'       => $user->username,
            'title'          => $vybe->title,
            'start_datetime' => $startDatetime->format('d.m.Y H:i:s'),
            'amount'         => $amount,
            'url'            => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseFinishPending(
        User $user,
        Vybe $vybe
    ) : void
    {
        PurchaseFinishPendingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param float $amount
     *
     * @return void
     */
    public function sendPurchaseOrderTipped(
        User $user,
        float $amount
    ) : void
    {
        PurchaseOrderTippedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'amount'   => $amount,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Reschedule

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Carbon $oldStartDatetime
     * @param Carbon $newStartDatetime
     *
     * @return void
     */
    public function sendRescheduleRequestReceived(
        User $user,
        Vybe $vybe,
        Carbon $oldStartDatetime,
        Carbon $newStartDatetime
    ) : void
    {
        RescheduleRequestReceivedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username'           => $user->username,
            'title'              => $vybe->title,
            'old_start_datetime' => $oldStartDatetime->format('d.m.Y H:i:s'),
            'new_start_datetime' => $newStartDatetime->format('d.m.Y H:i:s'),
            'url'                => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Carbon $newStartDatetime
     *
     * @return void
     */
    public function sendRescheduleRequestSuccessful(
        User $user,
        Vybe $vybe,
        Carbon $newStartDatetime
    ) : void
    {
        RescheduleRequestSuccessfulJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username'           => $user->username,
            'title'              => $vybe->title,
            'new_start_datetime' => $newStartDatetime->format('d.m.Y H:i:s'),
            'url'                => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendRescheduleRequestDeclined(
        User $user,
        Vybe $vybe
    ) : void
    {
        RescheduleRequestDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendRescheduleRequestCanceled(
        User $user,
        Vybe $vybe
    ) : void
    {
        RescheduleRequestCanceledJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Reviews

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendReviewVybeNew(
        User $user,
        Vybe $vybe
    ) : void
    {
        ReviewVybeNewJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendReviewProfileNew(
        User $user
    ) : void
    {
        ReviewProfileNewJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendReviewVybePending(
        User $user,
        Vybe $vybe
    ) : void
    {
        ReviewVybePendingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendReviewProfilePending(
        User $user
    ) : void
    {
        ReviewProfilePendingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Chat

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendChatMessageUnread(
        User $user
    ) : void
    {
        ChatMessageUnreadJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Withdrawals

    /**
     * @param User $user
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return void
     */
    public function sendWithdrawalRequestAccepted(
        User $user,
        WithdrawalReceipt $withdrawalReceipt
    ) : void
    {
        WithdrawalRequestAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'withdrawal_id' => $withdrawalReceipt->id,
            'url'           => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return void
     */
    public function sendWithdrawalRequestDeclined(
        User $user,
        WithdrawalReceipt $withdrawalReceipt
    ) : void
    {
        WithdrawalRequestDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'withdrawal_id' => $withdrawalReceipt->id,
            'url'           => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendWithdrawalMethodAccepted(
        User $user
    ) : void
    {
        WithdrawalMethodAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendWithdrawalMethodDeclined(
        User $user
    ) : void
    {
        WithdrawalMethodDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Add Funds

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAddFundsSuccessful(
        User $user
    ) : void
    {
        AddFundsSuccessfulJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Refunds

    /**
     * @param User $user
     * @param float $amount
     *
     * @return void
     */
    public function sendRefundReceived(
        User $user,
        float $amount
    ) : void
    {
        RefundReceivedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'amount' => $amount,
            'url'    => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Order $order
     * @param float $amount
     *
     * @return void
     */
    public function sendRefundVybeReceived(
        User $user,
        Vybe $vybe,
        Order $order,
        float $amount
    ) : void
    {
        RefundVybeReceivedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'order_id' => $order->id,
            'amount'   => $amount,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Affiliate Commissions

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionUser(
        User $user
    ) : void
    {
        AffiliateCommissionUserJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionPurchase(
        User $user
    ) : void
    {
        AffiliateCommissionPurchaseJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'url'      => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionSale(
        User $user
    ) : void
    {
        AffiliateCommissionSaleJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $user->username,
            'url'      => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // ID verification request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendIdVerificationDeclined(
        User $user
    ) : void
    {
        IdVerificationDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendIdVerificationAccepted(
        User $user
    ) : void
    {
        IdVerificationAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Billing change request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendBillingChangeDeclined(
        User $user
    ) : void
    {
        BillingChangeDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendBillingChangeAccepted(
        User $user
    ) : void
    {
        BillingChangeAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Deactivation request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendDeactivationRequestDeclined(
        User $user
    ) : void
    {
        DeactivationRequestDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendDeactivationRequestAccepted(
        User $user
    ) : void
    {
        DeactivationRequestAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Unsuspension request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendUnsuspensionRequestSubmitted(
        User $user
    ) : void
    {
        UnsuspensionRequestSubmittedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendUnsuspensionRequestDeclined(
        User $user
    ) : void
    {
        UnsuspensionRequestDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendUnsuspensionRequestAccepted(
        User $user
    ) : void
    {
        UnsuspensionRequestAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Suspension

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendSuspension(
        User $user
    ) : void
    {
        SuspensionAccountJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Deletion request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendDeletionRequestDeclined(
        User $user
    ) : void
    {
        DeletionRequestDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendDeletionRequestAccepted(
        User $user
    ) : void
    {
        DeletionRequestAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Tickets

    /**
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendTicketCreated(
        User $user,
        int $ticketId
    ) : void
    {
        TicketCreatedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'ticket_id' => $ticketId,
            'url'       => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendTicketResponse(
        User $user,
        int $ticketId
    ) : void
    {
        TicketResponseJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'ticket_id' => $ticketId,
            'url'       => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Dispute tickets

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param int $ticketId
     *
     * @return void
     */
    public function sendDisputeTicketOpened(
        User $user,
        Vybe $vybe,
        int $ticketId
    ) : void
    {
        DisputeTicketOpenedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'title'     => $vybe->title,
            'ticket_id' => $ticketId,
            'url'       => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param int $ticketId
     *
     * @return void
     */
    public function sendDisputeTicketResponse(
        User $user,
        Vybe $vybe,
        int $ticketId
    ) : void
    {
        DisputeTicketResponseJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'title'     => $vybe->title,
            'ticket_id' => $ticketId,
            'url'       => config('app.url')
        ]);
    }

    /**
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendDisputeTicketSettled(
        User $user,
        int $ticketId
    ) : void
    {
        DisputeTicketSettledJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'ticket_id' => $ticketId,
            'url'       => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Chargebacks

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param int $ticketId
     *
     * @return void
     */
    public function sendChargebackDisputeOpened(
        User $user,
        Vybe $vybe,
        int $ticketId
    ) : void
    {
        ChargebackDisputeOpenedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'title'     => $vybe->title,
            'ticket_id' => $ticketId,
            'url'       => config('app.url')
        ]);
    }

    //--------------------------------------------------------------------------
    // Account

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAccountEmailVerification(
        User $user
    ) : void
    {
        AccountEmailVerificationJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'email' => $user->email,
            'url'   => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAccountEmailResend(
        User $user
    ) : void
    {
        AccountEmailResendJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'email' => $user->email,
            'url'   => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAccountPasswordReset(
        User $user
    ) : void
    {
        AccountPasswordResetJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'email'                => $user->email,
            'password_reset_token' => $user->password_reset_token,
            'password_reset_url'   => config('auth.password.reset.url'),
        ]);
    }

    /**
     * @param Admin $admin
     *
     * @return void
     */
    public function sendAdminPasswordReset(
        Admin $admin
    ) : void
    {
        AccountPasswordResetJob::dispatch([
            'recipient' => [
                'auth_id' => $admin->auth_id,
                'locale'  => LanguageList::getEnglish()->iso,
                'email'   => $admin->email
            ],
            'email'                => $admin->email,
            'password_reset_token' => $admin->password_reset_token,
            'password_reset_url'   => config('admin.password.reset.url'),
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAccountPasswordChanged(
        User $user
    ) : void
    {
        AccountPasswordChangedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAccountProfileIncomplete(
        User $user
    ) : void
    {
        AccountProfileIncompleteJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'url' => config('app.url')
        ]);
    }
}
