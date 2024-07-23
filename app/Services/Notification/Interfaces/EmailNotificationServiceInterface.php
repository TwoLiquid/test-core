<?php

namespace App\Services\Notification\Interfaces;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;

/**
 * Interface EmailNotificationServiceInterface
 *
 * @package App\Services\Notification\Interfaces
 */
interface EmailNotificationServiceInterface
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
    public function sendFollowerNew(
        User $user,
        User $follower
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param User $follower
     *
     * @return void
     */
    public function sendFollowerVybeAvailable(
        User $user,
        User $follower
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     * @param User $follower
     *
     * @return void
     */
    public function sendFollowerEventAvailable(
        User $user,
        User $follower
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
    public function sendVybeActionRequired(
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
    public function sendSaleActionRequired(
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
     * @param float $amount
     *
     * @return void
     */
    public function sendSaleOrderTip(
        User $user,
        Vybe $vybe,
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
    public function sendPurchaseSuccessful(
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
    public function sendPurchaseOrderComplete(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendPurchasePaymentSuccessful(
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
    public function sendPurchaseVybeCanceled(
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
    public function sendPurchaseFinishPending(
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
    public function sendRescheduleRequestReceived(
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
    public function sendRescheduleRequestSuccessful(
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
    public function sendReviewVybePending(
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
    public function sendReviewProfilePending(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendChatMessageUnread(
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
    public function sendAddFundsSuccessful(
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
    public function sendDeactivationRequestAccepted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendUnsuspensionRequestSubmitted(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendUnsuspensionRequestDeclined(
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
    public function sendSuspension(
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
     *
     * @return void
     */
    public function sendDeletionRequestAccepted(
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
    public function sendTicketCreated(
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
    public function sendAccountEmailVerification(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAccountEmailResend(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAccountPasswordReset(
        User $user
    ) : void;

    /**
     * This method provides processing data
     *
     * @param Admin $admin
     *
     * @return void
     */
    public function sendAdminPasswordReset(
        Admin $admin
    ) : void;

    /**
     * This method provides processing data
     *
     * @param User $user
     *
     * @return void
     */
    public function sendAccountPasswordChanged(
        User $user
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
