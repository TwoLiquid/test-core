<?php

namespace App\Services\Notification;

use App\Jobs\Notification\Platform\Account\AccountBillingProceedJob;
use App\Jobs\Notification\Platform\Account\AccountBillingSavedJob;
use App\Jobs\Notification\Platform\Account\AccountEmailVerifiedJob;
use App\Jobs\Notification\Platform\Account\AccountProfileIncompleteJob;
use App\Jobs\Notification\Platform\AddFunds\AddFundsAddedJob;
use App\Jobs\Notification\Platform\AddFunds\AddFundsInvoiceJob;
use App\Jobs\Notification\Platform\AffiliateCommission\AffiliateCommissionPurchaseJob;
use App\Jobs\Notification\Platform\AffiliateCommission\AffiliateCommissionSaleJob;
use App\Jobs\Notification\Platform\AffiliateCommission\AffiliateCommissionUserJob;
use App\Jobs\Notification\Platform\AffiliateCommission\AffiliateCommissionVoidedJob;
use App\Jobs\Notification\Platform\Chargeback\ChargebackDisputeOpenedJob;
use App\Jobs\Notification\Platform\Follower\FollowerVybeAvailableJob;
use App\Jobs\Notification\Platform\Follower\FollowerEventAvailableJob;
use App\Jobs\Notification\Platform\Follower\FollowerYouJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseAcceptedJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseCanceledJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseDeclinedJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseFinishRequestedJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseNewJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseOrderExpiredJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseOrderNewJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseOrderPaidJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseOrderTippedJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseVybeCompleteJob;
use App\Jobs\Notification\Platform\Purchase\PurchaseVybeStartingJob;
use App\Jobs\Notification\Platform\Refund\RefundReceivedJob;
use App\Jobs\Notification\Platform\Refund\RefundVybeReceivedJob;
use App\Jobs\Notification\Platform\Reschedule\RescheduleRequestAcceptedJob;
use App\Jobs\Notification\Platform\Reschedule\RescheduleRequestCanceledJob;
use App\Jobs\Notification\Platform\Reschedule\RescheduleRequestDeclinedJob;
use App\Jobs\Notification\Platform\Reschedule\RescheduleRequestedJob;
use App\Jobs\Notification\Platform\Review\ReviewProfileNewJob;
use App\Jobs\Notification\Platform\Review\ReviewProfileWaitingJob;
use App\Jobs\Notification\Platform\Review\ReviewVybeNewJob;
use App\Jobs\Notification\Platform\Review\ReviewVybeWaitingJob;
use App\Jobs\Notification\Platform\Sale\SaleCanceledJob;
use App\Jobs\Notification\Platform\Sale\SaleFinishDeclinedJob;
use App\Jobs\Notification\Platform\Sale\SaleFinishedJob;
use App\Jobs\Notification\Platform\Sale\SaleOrderNewJob;
use App\Jobs\Notification\Platform\Sale\SaleOrderRespondedJob;
use App\Jobs\Notification\Platform\Sale\SaleOrderTippedJob;
use App\Jobs\Notification\Platform\Sale\SaleVybeSoldJob;
use App\Jobs\Notification\Platform\Sale\SaleVybeStartingJob;
use App\Jobs\Notification\Platform\Ticket\Dispute\DisputeTicketOpenedJob;
use App\Jobs\Notification\Platform\Ticket\Dispute\DisputeTicketResponseJob;
use App\Jobs\Notification\Platform\Ticket\Dispute\DisputeTicketSettledJob;
use App\Jobs\Notification\Platform\Ticket\TicketOpenedJob;
use App\Jobs\Notification\Platform\Ticket\TicketResolvedJob;
use App\Jobs\Notification\Platform\Ticket\TicketResponseJob;
use App\Jobs\Notification\Platform\User\Request\BillingChange\BillingChangeAcceptedJob;
use App\Jobs\Notification\Platform\User\Request\BillingChange\BillingChangeDeclinedJob;
use App\Jobs\Notification\Platform\User\Request\BillingChange\BillingChangeSubmittedJob;
use App\Jobs\Notification\Platform\User\Request\Deactivation\DeactivationRequestDeclinedJob;
use App\Jobs\Notification\Platform\User\Request\Deactivation\DeactivationRequestSubmittedJob;
use App\Jobs\Notification\Platform\User\Request\Deletion\DeletionRequestDeclinedJob;
use App\Jobs\Notification\Platform\User\Request\Deletion\DeletionRequestSubmittedJob;
use App\Jobs\Notification\Platform\User\Request\IdVerification\IdVerificationAcceptedJob;
use App\Jobs\Notification\Platform\User\Request\IdVerification\IdVerificationDeclinedJob;
use App\Jobs\Notification\Platform\User\Request\IdVerification\IdVerificationSubmittedJob;
use App\Jobs\Notification\Platform\User\Request\Unsuspension\UnsuspensionRequestAcceptedJob;
use App\Jobs\Notification\Platform\User\UserProfileApprovedJob;
use App\Jobs\Notification\Platform\User\UserProfileDeclinedJob;
use App\Jobs\Notification\Platform\User\UserProfilePendingJob;
use App\Jobs\Notification\Platform\User\UserWelcomeJob;
use App\Jobs\Notification\Platform\Vybe\Draft\VybeDraftDeletedJob;
use App\Jobs\Notification\Platform\Vybe\Draft\VybeDraftSavedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Change\VybeChangeAcceptedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Change\VybeChangeDeclinedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Change\VybeChangeRequestedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Deletion\VybeDeletionAcceptedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Deletion\VybeDeletionDeclinedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Deletion\VybeDeletionRequestedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Publish\VybePublishAcceptedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Publish\VybePublishDeclinedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Publish\VybePublishRequestedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Unpublish\VybeUnpublishAcceptedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Unpublish\VybeUnpublishDeclinedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Unpublish\VybeUnpublishRequestedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Unsuspension\VybeUnsuspensionAcceptedJob;
use App\Jobs\Notification\Platform\Vybe\Request\Unsuspension\VybeUnsuspensionDeclinedJob;
use App\Jobs\Notification\Platform\Vybe\VybeGlobalChangedJob;
use App\Jobs\Notification\Platform\Vybe\VybePausedJob;
use App\Jobs\Notification\Platform\Vybe\VybeSuspendedJob;
use App\Jobs\Notification\Platform\Vybe\VybeUnpausedJob;
use App\Jobs\Notification\Platform\Withdrawal\Method\WithdrawalMethodAcceptedJob;
use App\Jobs\Notification\Platform\Withdrawal\Method\WithdrawalMethodDeclinedJob;
use App\Jobs\Notification\Platform\Withdrawal\Method\WithdrawalMethodSubmittedJob;
use App\Jobs\Notification\Platform\Withdrawal\Request\WithdrawalRequestAcceptedJob;
use App\Jobs\Notification\Platform\Withdrawal\Request\WithdrawalRequestDeclinedJob;
use App\Jobs\Notification\Platform\Withdrawal\Request\WithdrawalRequestSubmittedJob;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Services\Notification\Interfaces\PlatformNotificationServiceInterface;
use Carbon\Carbon;

/**
 * Class PlatformNotificationService
 *
 * @package App\Services\Notification
 */
class PlatformNotificationService implements PlatformNotificationServiceInterface
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
         * Sending user register welcome platform notification
         */
        UserWelcomeJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendUserProfilePending(
        User $user
    ) : void
    {
        /**
         * Sending user profile pending request platform notification
         */
        UserProfilePendingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
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
         * Sending user profile request approved platform notification
         */
        UserProfileApprovedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
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
         * Sending user profile request decline platform notification
         */
        UserProfileDeclinedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
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
    public function sendFollowerYou(
        User $user,
        User $follower
    ) : void
    {
        FollowerYouJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $follower->username
        ]);
    }

    /**
     * @param User $user
     * @param User $follower
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendFollowerVybeAvailable(
        User $user,
        User $follower,
        Vybe $vybe
    ) : void
    {
        FollowerVybeAvailableJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $follower->username,
            'vybe_id'  => $vybe->id
        ]);
    }

    /**
     * @param User $user
     * @param User $follower
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendFollowerEventAvailable(
        User $user,
        User $follower,
        Vybe $vybe
    ) : void
    {
        FollowerEventAvailableJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso,
                'email'   => $user->email
            ],
            'username' => $follower->username,
            'vybe_id'  => $vybe->id
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
    public function sendVybePublishRequested(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybePublishRequestedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'title' => $vybe->title
        ]);
    }

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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeChangeRequested(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeChangeRequestedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDraftSaved(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeDraftSavedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendVybeDraftDeleted(
        User $user
    ) : void
    {
        VybeDraftDeletedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybePaused(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybePausedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpaused(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeUnpausedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpublishRequested(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeUnpublishRequestedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'title' => $vybe->title
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDeletionRequested(
        User $user,
        Vybe $vybe
    ) : void
    {
        VybeDeletionRequestedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'title' => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'vybe_id' => $vybe->id,
            'title'   => $vybe->title
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendVybeGlobalChanged(
        User $user
    ) : void
    {
        VybeGlobalChangedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username'       => $user->username,
            'title'          => $vybe->title,
            'start_datetime' => $startDatetime->format('m.d.Y H:i:s')
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
    public function sendSaleOrderResponded(
        User $user,
        Vybe $vybe,
        Sale $sale,
        Carbon $datetime
    ) : void
    {
        SaleOrderRespondedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'title'    => $vybe->title,
            'sale_id'  => $sale->id,
            'datetime' => $datetime->format('H:i:s')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleOrderTipped(
        User $user,
        Vybe $vybe
    ) : void
    {
        SaleOrderTippedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'title' => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseNew(
        User $user,
        Vybe $vybe
    ) : void
    {
        PurchaseNewJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendPurchaseOrderNew(
        User $user
    ) : void
    {
        PurchaseOrderNewJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendPurchaseOrderPaid(
        User $user
    ) : void
    {
        PurchaseOrderPaidJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username'       => $user->username,
            'title'          => $vybe->title,
            'start_datetime' => $startDatetime->format('d.m.Y H:i:s')
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'amount'   => $amount
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
    public function sendPurchaseCanceled(
        User $user,
        Vybe $vybe,
        Carbon $startDatetime,
        float $amount
    ) : void
    {
        PurchaseCanceledJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username'       => $user->username,
            'title'          => $vybe->title,
            'start_datetime' => $startDatetime->format('d.m.Y H:i:s'),
            'amount'         => $amount
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseFinishRequested(
        User $user,
        Vybe $vybe
    ) : void
    {
        PurchaseFinishRequestedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'amount'   => $amount
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
    public function sendRescheduleRequested(
        User $user,
        Vybe $vybe,
        Carbon $oldStartDatetime,
        Carbon $newStartDatetime
    ) : void
    {
        RescheduleRequestedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username'           => $user->username,
            'title'              => $vybe->title,
            'old_start_datetime' => $oldStartDatetime->format('d.m.Y H:i:s'),
            'new_start_datetime' => $newStartDatetime->format('d.m.Y H:i:s')
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param Carbon $newStartDatetime
     *
     * @return void
     */
    public function sendRescheduleRequestAccepted(
        User $user,
        Vybe $vybe,
        Carbon $newStartDatetime
    ) : void
    {
        RescheduleRequestAcceptedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username'           => $user->username,
            'title'              => $vybe->title,
            'new_start_datetime' => $newStartDatetime->format('d.m.Y H:i:s')
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'vybe_id'  => $vybe->id
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendReviewVybeWaiting(
        User $user,
        Vybe $vybe
    ) : void
    {
        ReviewVybeWaitingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendReviewProfileWaiting(
        User $user
    ) : void
    {
        ReviewProfileWaitingJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username
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
    public function sendWithdrawalRequestSubmitted(
        User $user,
        WithdrawalReceipt $withdrawalReceipt
    ) : void
    {
        WithdrawalRequestSubmittedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'withdrawal_id' => $withdrawalReceipt->id
        ]);
    }

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
                'locale'  => $user->getLanguage()->iso
            ],
            'withdrawal_id' => $withdrawalReceipt->id
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
                'locale'  => $user->getLanguage()->iso
            ],
            'withdrawal_id' => $withdrawalReceipt->id
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendWithdrawalMethodSubmitted(
        User $user
    ) : void
    {
        WithdrawalMethodSubmittedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
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
                'locale'  => $user->getLanguage()->iso
            ]
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
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    //--------------------------------------------------------------------------
    // Add Funds

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAddFundsAdded(
        User $user
    ) : void
    {
        AddFundsAddedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAddFundsInvoice(
        User $user
    ) : void
    {
        AddFundsInvoiceJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
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
                'locale'  => $user->getLanguage()->iso
            ],
            'amount' => $amount
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     * @param float $amount
     *
     * @return void
     */
    public function sendRefundVybeReceived(
        User $user,
        Vybe $vybe,
        float $amount
    ) : void
    {
        RefundVybeReceivedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username,
            'title'    => $vybe->title,
            'amount'   => $amount
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionVoided(
        User $user
    ) : void
    {
        AffiliateCommissionVoidedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'username' => $user->username
        ]);
    }

    //--------------------------------------------------------------------------
    // ID verification request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendIdVerificationSubmitted(
        User $user
    ) : void
    {
        IdVerificationSubmittedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

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
                'locale'  => $user->getLanguage()->iso
            ]
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
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    //--------------------------------------------------------------------------
    // Billing change request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendBillingChangeSubmitted(
        User $user
    ) : void
    {
        BillingChangeSubmittedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

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
                'locale'  => $user->getLanguage()->iso
            ]
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
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    //--------------------------------------------------------------------------
    // Deactivation request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendDeactivationRequestSubmitted(
        User $user
    ) : void
    {
        DeactivationRequestSubmittedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

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
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    //--------------------------------------------------------------------------
    // Unsuspension request

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
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    //--------------------------------------------------------------------------
    // Deletion request

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendDeletionRequestSubmitted(
        User $user
    ) : void
    {
        DeletionRequestSubmittedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

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
                'locale'  => $user->getLanguage()->iso
            ]
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
    public function sendTicketOpened(
        User $user,
        int $ticketId
    ) : void
    {
        TicketOpenedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'ticket_id' => $ticketId
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
                'locale'  => $user->getLanguage()->iso
            ],
            'ticket_id' => $ticketId
        ]);
    }

    /**
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendTicketResolved(
        User $user,
        int $ticketId
    ) : void
    {
        TicketResolvedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'ticket_id' => $ticketId
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
                'locale'  => $user->getLanguage()->iso
            ],
            'title'     => $vybe->title,
            'ticket_id' => $ticketId
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
                'locale'  => $user->getLanguage()->iso
            ],
            'title'     => $vybe->title,
            'ticket_id' => $ticketId
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
                'locale'  => $user->getLanguage()->iso
            ],
            'ticket_id' => $ticketId
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
                'locale'  => $user->getLanguage()->iso
            ],
            'username'  => $user->username,
            'title'     => $vybe->title,
            'ticket_id' => $ticketId
        ]);
    }

    //--------------------------------------------------------------------------
    // Account

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAccountEmailVerified(
        User $user
    ) : void
    {
        AccountEmailVerifiedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function sendAccountBillingSaved(
        User $user
    ) : void
    {
        AccountBillingSavedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendAccountBillingProceed(
        User $user,
        Vybe $vybe
    ) : void
    {
        AccountBillingProceedJob::dispatch([
            'recipient' => [
                'auth_id' => $user->auth_id,
                'locale'  => $user->getLanguage()->iso
            ],
            'title' => $vybe->title
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
                'locale'  => $user->getLanguage()->iso
            ]
        ]);
    }
}
