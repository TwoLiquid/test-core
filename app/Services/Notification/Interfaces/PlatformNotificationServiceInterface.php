<?php

namespace App\Services\Notification\Interfaces;

use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;

/**
 * Interface PlatformNotificationServiceInterface
 *
 * @package App\Services\Notification\Interfaces
 */
interface PlatformNotificationServiceInterface
{
    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendUserWelcome(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendUserProfilePending(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendUserProfileApproved(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendUserProfileDeclined(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param User $follower
     *
     * @return void
     */
    public function sendFollowerYou(
        User $user,
        User $follower
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybePublishRequested(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybePublishDeclined(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybePublishAccepted(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeChangeRequested(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeChangeDeclined(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeChangeAccepted(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDraftSaved(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendVybeDraftDeleted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybePaused(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpaused(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpublishRequested(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpublishDeclined(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnpublishAccepted(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDeletionRequested(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDeletionDeclined(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeDeletionAccepted(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeSuspended(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnsuspensionDeclined(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendVybeUnsuspensionAccepted(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendVybeGlobalChanged(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleVybeStarting(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleOrderNew(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleCanceled(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleFinished(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleFinishDeclined(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendSaleOrderTipped(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseOrderExpired(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseVybeStarting(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseVybeComplete(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseNew(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendPurchaseOrderNew(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendPurchaseOrderPaid(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendPurchaseFinishRequested(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param float $amount
     *
     * @return void
     */
    public function sendPurchaseOrderTipped(
        User $user,
        float $amount
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendRescheduleRequestDeclined(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendRescheduleRequestCanceled(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendReviewVybeNew(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendReviewProfileNew(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendReviewVybeWaiting(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendReviewProfileWaiting(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return void
     */
    public function sendWithdrawalRequestSubmitted(
        User $user,
        WithdrawalReceipt $withdrawalReceipt
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return void
     */
    public function sendWithdrawalRequestAccepted(
        User $user,
        WithdrawalReceipt $withdrawalReceipt
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return void
     */
    public function sendWithdrawalRequestDeclined(
        User $user,
        WithdrawalReceipt $withdrawalReceipt
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendWithdrawalMethodSubmitted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendWithdrawalMethodAccepted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendWithdrawalMethodDeclined(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAddFundsAdded(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAddFundsInvoice(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param float $amount
     *
     * @return void
     */
    public function sendRefundReceived(
        User $user,
        float $amount
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionUser(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionPurchase(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionSale(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAffiliateCommissionVoided(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendIdVerificationSubmitted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendIdVerificationDeclined(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendIdVerificationAccepted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendBillingChangeSubmitted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendBillingChangeDeclined(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendBillingChangeAccepted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendDeactivationRequestSubmitted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendDeactivationRequestDeclined(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendUnsuspensionRequestAccepted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendDeletionRequestSubmitted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendDeletionRequestDeclined(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendTicketOpened(
        User $user,
        int $ticketId
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendTicketResponse(
        User $user,
        int $ticketId
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendTicketResolved(
        User $user,
        int $ticketId
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param int $ticketId
     *
     * @return void
     */
    public function sendDisputeTicketSettled(
        User $user,
        int $ticketId
    ) : void;

    /**
     * This method provides processing data
     *
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
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAccountEmailVerified(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAccountBillingSaved(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param Vybe $vybe
     *
     * @return void
     */
    public function sendAccountBillingProceed(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAccountProfileIncomplete(
        User $user
    ) : void;
}
