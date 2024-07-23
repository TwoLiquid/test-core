<?php

namespace App\Console\Commands\Notification;

use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Services\Notification\PlatformNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class PlatformNotificationTest
 *
 * @package App\Console\Commands\Notification
 */
class PlatformNotificationTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform-notification:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends test platform notifications.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        /** @var PlatformNotificationService $platformNotificationService */
        $platformNotificationService = app(PlatformNotificationService::class);

        /** @var User $user */
        $user = User::find(1);

        /** @var User $follower */
        $follower = User::find(2);

        /** @var Vybe $vybe */
        $vybe = Vybe::find(1);

        /** @var Sale $sale */
        $sale = Sale::find(1);

        /** @var WithdrawalReceipt $withdrawalReceipt */
        $withdrawalReceipt = WithdrawalReceipt::find(1);

        //--------------------------------------------------------------------------
        // Users

        $platformNotificationService->sendUserWelcome(
            $user
        );

        $platformNotificationService->sendUserProfilePending(
            $user
        );

        $platformNotificationService->sendUserProfileApproved(
            $user
        );

        $platformNotificationService->sendUserProfileDeclined(
            $user
        );

        //--------------------------------------------------------------------------
        // Followers

        $platformNotificationService->sendFollowerYou(
            $user,
            $follower
        );

        $platformNotificationService->sendFollowerVybeAvailable(
            $user,
            $follower,
            $vybe
        );

        $platformNotificationService->sendFollowerEventAvailable(
            $user,
            $follower,
            $vybe
        );

        //--------------------------------------------------------------------------
        // Vybes

        $platformNotificationService->sendVybePublishRequested(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybePublishDeclined(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybePublishAccepted(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeChangeRequested(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeChangeDeclined(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeChangeAccepted(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeDraftSaved(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeDraftDeleted(
            $user
        );

        $platformNotificationService->sendVybePaused(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeUnpaused(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeUnpublishRequested(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeUnpublishDeclined(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeUnpublishAccepted(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeDeletionRequested(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeDeletionDeclined(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeDeletionAccepted(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeSuspended(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeUnsuspensionDeclined(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeUnsuspensionAccepted(
            $user,
            $vybe
        );

        $platformNotificationService->sendVybeGlobalChanged(
            $user
        );

        //--------------------------------------------------------------------------
        // Sales

        $platformNotificationService->sendSaleVybeStarting(
            $user,
            $vybe
        );

        $platformNotificationService->sendSaleOrderNew(
            $user,
            $vybe
        );

        $platformNotificationService->sendSaleVybeSold(
            $user,
            $vybe,
            Carbon::now()
        );

        $platformNotificationService->sendSaleCanceled(
            $user,
            $vybe
        );

        $platformNotificationService->sendSaleFinished(
            $user,
            $vybe
        );

        $platformNotificationService->sendSaleFinishDeclined(
            $user,
            $vybe
        );

        $platformNotificationService->sendSaleOrderResponded(
            $user,
            $vybe,
            $sale,
            Carbon::now()
        );

        $platformNotificationService->sendSaleOrderTipped(
            $user,
            $vybe
        );

        //--------------------------------------------------------------------------
        // Purchases

        $platformNotificationService->sendPurchaseOrderExpired(
            $user,
            $vybe
        );

        $platformNotificationService->sendPurchaseVybeStarting(
            $user,
            $vybe
        );

        $platformNotificationService->sendPurchaseVybeComplete(
            $user,
            $vybe
        );

        $platformNotificationService->sendPurchaseNew(
            $user,
            $vybe
        );

        $platformNotificationService->sendPurchaseOrderNew(
            $user
        );

        $platformNotificationService->sendPurchaseOrderPaid(
            $user
        );

        $platformNotificationService->sendPurchaseAccepted(
            $user,
            $vybe,
            Carbon::now()
        );

        $platformNotificationService->sendPurchaseDeclined(
            $user,
            $vybe,
            1000
        );

        $platformNotificationService->sendPurchaseCanceled(
            $user,
            $vybe,
            Carbon::now(),
            1000
        );

        $platformNotificationService->sendPurchaseFinishRequested(
            $user,
            $vybe
        );

        $platformNotificationService->sendPurchaseOrderTipped(
            $user,
            100
        );

        //--------------------------------------------------------------------------
        // Reschedule

        $platformNotificationService->sendRescheduleRequested(
            $user,
            $vybe,
            Carbon::now(),
            Carbon::now()->addSeconds(3600),
        );

        $platformNotificationService->sendRescheduleRequestAccepted(
            $user,
            $vybe,
            Carbon::now()
        );

        $platformNotificationService->sendRescheduleRequestDeclined(
            $user,
            $vybe
        );

        $platformNotificationService->sendRescheduleRequestCanceled(
            $user,
            $vybe
        );

        //--------------------------------------------------------------------------
        // Reviews

        $platformNotificationService->sendReviewVybeNew(
            $user,
            $vybe
        );

        $platformNotificationService->sendReviewProfileNew(
            $user
        );

        $platformNotificationService->sendReviewVybeWaiting(
            $user,
            $vybe
        );

        $platformNotificationService->sendReviewProfileWaiting(
            $user
        );

        //--------------------------------------------------------------------------
        // Withdrawals

        $platformNotificationService->sendWithdrawalRequestSubmitted(
            $user,
            $withdrawalReceipt
        );

        $platformNotificationService->sendWithdrawalRequestAccepted(
            $user,
            $withdrawalReceipt
        );

        $platformNotificationService->sendWithdrawalRequestDeclined(
            $user,
            $withdrawalReceipt
        );

        $platformNotificationService->sendWithdrawalMethodSubmitted(
            $user
        );

        $platformNotificationService->sendWithdrawalMethodAccepted(
            $user
        );

        $platformNotificationService->sendWithdrawalMethodDeclined(
            $user
        );

        //--------------------------------------------------------------------------
        // Add Funds

        $platformNotificationService->sendAddFundsAdded(
            $user
        );

        $platformNotificationService->sendAddFundsInvoice(
            $user
        );

        //--------------------------------------------------------------------------
        // Refunds

        $platformNotificationService->sendRefundReceived(
            $user,
            1000
        );

        $platformNotificationService->sendRefundVybeReceived(
            $user,
            $vybe,
            1000
        );

        //--------------------------------------------------------------------------
        // Affiliate Commissions

        $platformNotificationService->sendAffiliateCommissionUser(
            $user
        );

        $platformNotificationService->sendAffiliateCommissionPurchase(
            $user
        );

        $platformNotificationService->sendAffiliateCommissionSale(
            $user
        );

        $platformNotificationService->sendAffiliateCommissionVoided(
            $user
        );

        //--------------------------------------------------------------------------
        // ID verification request

        $platformNotificationService->sendIdVerificationSubmitted(
            $user
        );

        $platformNotificationService->sendIdVerificationDeclined(
            $user
        );

        $platformNotificationService->sendIdVerificationAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Billing change request

        $platformNotificationService->sendBillingChangeSubmitted(
            $user
        );

        $platformNotificationService->sendBillingChangeDeclined(
            $user
        );

        $platformNotificationService->sendBillingChangeAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Deactivation request

        $platformNotificationService->sendDeactivationRequestSubmitted(
            $user
        );

        $platformNotificationService->sendDeactivationRequestDeclined(
            $user
        );

        //--------------------------------------------------------------------------
        // Unsuspension request

        $platformNotificationService->sendUnsuspensionRequestAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Deletion request

        $platformNotificationService->sendDeletionRequestSubmitted(
            $user
        );

        $platformNotificationService->sendDeletionRequestDeclined(
            $user
        );

        //--------------------------------------------------------------------------
        // Tickets

        $platformNotificationService->sendTicketOpened(
            $user,
            200
        );

        $platformNotificationService->sendTicketResponse(
            $user,
            200
        );

        $platformNotificationService->sendTicketResolved(
            $user,
            200
        );

        //--------------------------------------------------------------------------
        // Dispute tickets

        $platformNotificationService->sendDisputeTicketOpened(
            $user,
            $vybe,
            210
        );

        $platformNotificationService->sendDisputeTicketResponse(
            $user,
            $vybe,
            210
        );

        $platformNotificationService->sendDisputeTicketSettled(
            $user,
            210
        );

        //--------------------------------------------------------------------------
        // Chargebacks

        $platformNotificationService->sendChargebackDisputeOpened(
            $user,
            $vybe,
            210
        );

        //--------------------------------------------------------------------------
        // Account

        $platformNotificationService->sendAccountEmailVerified(
            $user
        );

        $platformNotificationService->sendAccountBillingSaved(
            $user
        );

        $platformNotificationService->sendAccountBillingProceed(
            $user,
            $vybe
        );

        $platformNotificationService->sendAccountProfileIncomplete(
            $user
        );

        return 0;
    }
}
