<?php

namespace App\Console\Commands\Notification;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Services\Notification\EmailNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class EmailNotificationTest
 *
 * @package App\Console\Commands\Notification
 */
class EmailNotificationTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-notification:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends test email notifications.';

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
        /** @var EmailNotificationService $emailNotificationService */
        $emailNotificationService = app(EmailNotificationService::class);

        /** @var User $user */
        $user = User::find(1);

        /** @var User $follower */
        $follower = User::find(2);

        /** @var Admin $admin */
        $admin = Admin::find(1);

        /** @var Vybe $vybe */
        $vybe = Vybe::find(1);

        /** @var Sale $sale */
        $sale = Sale::find(1);

        /** @var Order $order */
        $order = Order::find(1);

        /** @var WithdrawalReceipt $withdrawalReceipt */
        $withdrawalReceipt = WithdrawalReceipt::find(1);

        //--------------------------------------------------------------------------
        // Users

        $emailNotificationService->sendUserWelcome(
            $user
        );

        $emailNotificationService->sendUserProfileApproved(
            $user
        );

        $emailNotificationService->sendUserProfileDeclined(
            $user
        );

        //--------------------------------------------------------------------------
        // Followers

        $emailNotificationService->sendFollowerNew(
            $user,
            $follower
        );

        $emailNotificationService->sendFollowerVybeAvailable(
            $user,
            $follower
        );

        $emailNotificationService->sendFollowerEventAvailable(
            $user,
            $follower
        );

        //--------------------------------------------------------------------------
        // Vybes

        $emailNotificationService->sendVybePublishDeclined(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybePublishAccepted(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeUnpublishDeclined(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeUnpublishAccepted(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeDeletionDeclined(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeDeletionAccepted(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeSuspended(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeUnsuspensionDeclined(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeUnsuspensionAccepted(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeChangeDeclined(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeChangeAccepted(
            $user,
            $vybe
        );

        $emailNotificationService->sendVybeActionRequired(
            $user,
            $vybe
        );

        //--------------------------------------------------------------------------
        // Sales

        $emailNotificationService->sendSaleVybeStarting(
            $user,
            $vybe
        );

        $emailNotificationService->sendSaleOrderNew(
            $user,
            $vybe
        );

        $emailNotificationService->sendSaleVybeSold(
            $user,
            $vybe,
            Carbon::now()
        );

        $emailNotificationService->sendSaleCanceled(
            $user,
            $vybe
        );

        $emailNotificationService->sendSaleFinished(
            $user,
            $vybe
        );

        $emailNotificationService->sendSaleFinishDeclined(
            $user,
            $vybe
        );

        $emailNotificationService->sendSaleActionRequired(
            $user,
            $vybe,
            $sale,
            Carbon::now()
        );

        $emailNotificationService->sendSaleOrderTip(
            $user,
            $vybe,
            10
        );

        //--------------------------------------------------------------------------
        // Purchases

        $emailNotificationService->sendPurchaseOrderExpired(
            $user,
            $vybe
        );

        $emailNotificationService->sendPurchaseVybeStarting(
            $user,
            $vybe
        );

        $emailNotificationService->sendPurchaseVybeComplete(
            $user,
            $vybe
        );

        $emailNotificationService->sendPurchaseSuccessful(
            $user,
            $vybe
        );

        $emailNotificationService->sendPurchaseOrderComplete(
            $user
        );

        $emailNotificationService->sendPurchasePaymentSuccessful(
            $user
        );

        $emailNotificationService->sendPurchaseAccepted(
            $user,
            $vybe,
            Carbon::now()
        );

        $emailNotificationService->sendPurchaseDeclined(
            $user,
            $vybe,
            1000
        );

        $emailNotificationService->sendPurchaseVybeCanceled(
            $user,
            $vybe,
            Carbon::now(),
            1000
        );

        $emailNotificationService->sendPurchaseFinishPending(
            $user,
            $vybe
        );

        $emailNotificationService->sendPurchaseOrderTipped(
            $user,
            100
        );

        //--------------------------------------------------------------------------
        // Reschedule

        $emailNotificationService->sendRescheduleRequestReceived(
            $user,
            $vybe,
            Carbon::now(),
            Carbon::now()->addSeconds(3600),
        );

        $emailNotificationService->sendRescheduleRequestSuccessful(
            $user,
            $vybe,
            Carbon::now()
        );

        $emailNotificationService->sendRescheduleRequestDeclined(
            $user,
            $vybe
        );

        $emailNotificationService->sendRescheduleRequestCanceled(
            $user,
            $vybe
        );

        //--------------------------------------------------------------------------
        // Reviews

        $emailNotificationService->sendReviewVybeNew(
            $user,
            $vybe
        );

        $emailNotificationService->sendReviewProfileNew(
            $user
        );

        $emailNotificationService->sendReviewVybePending(
            $user,
            $vybe
        );

        $emailNotificationService->sendReviewProfilePending(
            $user
        );

        //--------------------------------------------------------------------------
        // Chat

        $emailNotificationService->sendChatMessageUnread(
            $user
        );

        //--------------------------------------------------------------------------
        // Withdrawals

        $emailNotificationService->sendWithdrawalRequestAccepted(
            $user,
            $withdrawalReceipt
        );

        $emailNotificationService->sendWithdrawalRequestDeclined(
            $user,
            $withdrawalReceipt
        );

        $emailNotificationService->sendWithdrawalMethodAccepted(
            $user
        );

        $emailNotificationService->sendWithdrawalMethodDeclined(
            $user
        );

        //--------------------------------------------------------------------------
        // Add Funds

        $emailNotificationService->sendAddFundsSuccessful(
            $user
        );

        //--------------------------------------------------------------------------
        // Refunds

        $emailNotificationService->sendRefundReceived(
            $user,
            1000
        );

        $emailNotificationService->sendRefundVybeReceived(
            $user,
            $vybe,
            $order,
            1000
        );

        //--------------------------------------------------------------------------
        // Affiliate Commissions

        $emailNotificationService->sendAffiliateCommissionUser(
            $user
        );

        $emailNotificationService->sendAffiliateCommissionPurchase(
            $user
        );

        $emailNotificationService->sendAffiliateCommissionSale(
            $user
        );

        //--------------------------------------------------------------------------
        // ID verification request

        $emailNotificationService->sendIdVerificationDeclined(
            $user
        );

        $emailNotificationService->sendIdVerificationAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Billing change request

        $emailNotificationService->sendBillingChangeDeclined(
            $user
        );

        $emailNotificationService->sendBillingChangeAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Deactivation request

        $emailNotificationService->sendDeactivationRequestDeclined(
            $user
        );

        $emailNotificationService->sendDeactivationRequestAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Unsuspension request

        $emailNotificationService->sendUnsuspensionRequestSubmitted(
            $user
        );

        $emailNotificationService->sendUnsuspensionRequestDeclined(
            $user
        );

        $emailNotificationService->sendUnsuspensionRequestAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Suspension

        $emailNotificationService->sendSuspension(
            $user
        );

        //--------------------------------------------------------------------------
        // Deletion request

        $emailNotificationService->sendDeletionRequestDeclined(
            $user
        );

        $emailNotificationService->sendDeletionRequestAccepted(
            $user
        );

        //--------------------------------------------------------------------------
        // Tickets

        $emailNotificationService->sendTicketCreated(
            $user,
            200
        );

        $emailNotificationService->sendTicketResponse(
            $user,
            200
        );

        //--------------------------------------------------------------------------
        // Dispute tickets

        $emailNotificationService->sendDisputeTicketOpened(
            $user,
            $vybe,
            210
        );

        $emailNotificationService->sendDisputeTicketResponse(
            $user,
            $vybe,
            210
        );

        $emailNotificationService->sendDisputeTicketSettled(
            $user,
            210
        );

        //--------------------------------------------------------------------------
        // Chargebacks

        $emailNotificationService->sendChargebackDisputeOpened(
            $user,
            $vybe,
            210
        );

        //--------------------------------------------------------------------------
        // Account

        $emailNotificationService->sendAccountEmailVerification(
            $user
        );

        $emailNotificationService->sendAccountEmailResend(
            $user
        );

        $emailNotificationService->sendAccountPasswordReset(
            $user
        );

        $emailNotificationService->sendAdminPasswordReset(
            $admin
        );

        $emailNotificationService->sendAccountPasswordChanged(
            $user
        );

        $emailNotificationService->sendAccountProfileIncomplete(
            $user
        );

        return 0;
    }
}
