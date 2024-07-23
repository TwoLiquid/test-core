<?php

namespace App\Services\Admin;

use App\Exceptions\DatabaseException;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Repositories\Order\OrderItemFinishRequestRepository;
use App\Repositories\Order\OrderItemPendingRequestRepository;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\Order\OrderItemRescheduleRequestRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\Receipt\AddFundsReceiptRepository;
use App\Repositories\Receipt\WithdrawalReceiptRepository;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Repositories\Sale\SaleRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Repositories\Tip\TipRepository;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Services\Admin\Interfaces\AdminNavbarServiceInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Class AdminNavbarService
 *
 * @package App\Services\Admin
 */
class AdminNavbarService implements AdminNavbarServiceInterface
{
    /**
     * @var AddFundsReceiptRepository
     */
    protected AddFundsReceiptRepository $addFundsReceiptRepository;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var DeviceSuggestionRepository
     */
    protected DeviceSuggestionRepository $deviceSuggestionRepository;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var OrderInvoiceRepository
     */
    protected OrderInvoiceRepository $orderInvoiceRepository;

    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderItemPendingRequestRepository
     */
    protected OrderItemPendingRequestRepository $orderItemPendingRequestRepository;

    /**
     * @var OrderItemRescheduleRequestRepository
     */
    protected OrderItemRescheduleRequestRepository $orderItemRescheduleRequestRepository;

    /**
     * @var OrderItemFinishRequestRepository
     */
    protected OrderItemFinishRequestRepository $orderItemFinishRequestRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var SaleRepository
     */
    protected SaleRepository $saleRepository;

    /**
     * @var TipRepository
     */
    protected TipRepository $tipRepository;

    /**
     * @var TipInvoiceRepository
     */
    protected TipInvoiceRepository $tipInvoiceRepository;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var VybeChangeRequestRepository
     */
    protected VybeChangeRequestRepository $vybeChangeRequestRepository;

    /**
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * @var WithdrawalReceiptRepository
     */
    protected WithdrawalReceiptRepository $withdrawalReceiptRepository;

    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * AdminNavbarService constructor
     */
    public function __construct()
    {
        /** @var AddFundsReceiptRepository addFundsReceiptRepository */
        $this->addFundsReceiptRepository = new AddFundsReceiptRepository();

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

        /** @var OrderRepository orderRepository */
        $this->orderRepository = new OrderRepository();

        /** @var OrderInvoiceRepository orderInvoiceRepository */
        $this->orderInvoiceRepository = new OrderInvoiceRepository();

        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderItemFinishRequestRepository orderItemPendingRequestRepository */
        $this->orderItemPendingRequestRepository = new OrderItemPendingRequestRepository();

        /** @var OrderItemRescheduleRequestRepository orderItemRescheduleRequestRepository */
        $this->orderItemRescheduleRequestRepository = new OrderItemRescheduleRequestRepository();

        /** @var OrderItemFinishRequestRepository orderItemFinishRequestRepository */
        $this->orderItemFinishRequestRepository = new OrderItemFinishRequestRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var SaleRepository saleRepository */
        $this->saleRepository = new SaleRepository();

        /** @var TipRepository tipRepository */
        $this->tipRepository = new TipRepository();

        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var VybeChangeRequestRepository vybeChangeRequestRepository */
        $this->vybeChangeRequestRepository = new VybeChangeRequestRepository();

        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();

        /** @var VybeUnsuspendRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();

        /** @var WithdrawalReceiptRepository withdrawalReceiptRepository */
        $this->withdrawalReceiptRepository = new WithdrawalReceiptRepository();

        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();
    }

    //--------------------------------------------------------------------------
    // Getting counters by group functions

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function getSuggestions() : array
    {
        return [
            'suggestions' => array_sum([
                $this->csauSuggestionRepository->getAllCount(),
                $this->deviceSuggestionRepository->getAllCount()
            ]),
            'csau'        => $this->csauSuggestionRepository->getAllCount(),
            'devices'     => $this->deviceSuggestionRepository->getAllCount()
        ];
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function getUsers() : array
    {
        return [
            'buyers'     => $this->userRepository->getBuyersCount(),
            'sellers'    => $this->userRepository->getSellersCount(),
            'affiliates' => $this->userRepository->getAffiliatesCount(),
            'all_users'  => $this->userRepository->getAllCount()
        ];
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function getRequests() : array
    {
        return [
            'user_profile_requests'         => $this->userProfileRequestRepository->getAllCount(),
            'user_id_verification_requests' => $this->userIdVerificationRequestRepository->getAllCount(),
            'user_deactivation_requests'    => $this->userDeactivationRequestRepository->getAllCount(),
            'user_unsuspend_requests'       => $this->userUnsuspendRequestRepository->getAllCount(),
            'user_deletion_requests'        => $this->userDeletionRequestRepository->getAllCount(),
            'vybe_change_requests'          => $this->vybeChangeRequestRepository->getAllCount(),
            'vybe_publish_requests'         => $this->vybePublishRequestRepository->getAllCount(),
            'vybe_unpublish_requests'       => $this->vybeUnpublishRequestRepository->getAllCount(),
            'vybe_unsuspend_requests'       => $this->vybeUnsuspendRequestRepository->getAllCount(),
            'vybe_deletion_requests'        => $this->vybeDeletionRequestRepository->getAllCount(),
            'billing_change_requests'       => $this->billingChangeRequestRepository->getAllCount(),
            'withdrawal_requests'           => $this->withdrawalRequestRepository->getAllCount(),
            'payout_method_requests'        => $this->payoutMethodRequestRepository->getAllCount()
        ];
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function getOrders() : array
    {
        return [
            'order_overview'      => $this->orderRepository->getAllCount(),
            'sale_overview'       => $this->saleRepository->getAllCount(),
            'order_items'         => $this->orderItemRepository->getAllCount(),
            'tips'                => $this->tipRepository->getAllCount(),
            'pending_requests'    => $this->orderItemPendingRequestRepository->getAllCount(),
            'reschedule_requests' => $this->orderItemRescheduleRequestRepository->getAllCount(),
            'finish_requests'     => $this->orderItemFinishRequestRepository->getAllCount()
        ];
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function getInvoices() : array
    {
        return [
            'invoices_for_buyer'         => $this->orderInvoiceRepository->getAllForBuyerCount(),
            'invoices_for_seller'        => $this->orderInvoiceRepository->getAllForSellerCount(),
            'invoices_for_affiliates'    => $this->orderInvoiceRepository->getAllForAffiliateCount(),
            'seller_withdrawal_receipts' => $this->withdrawalReceiptRepository->getAllCount(),
            'custom_invoices'            => 0,
            'add_funds_receipts'         => $this->addFundsReceiptRepository->getAllCount(),
            'tip_invoices_for_buyer'     => $this->tipInvoiceRepository->getAllForBuyerCount(),
            'tip_invoices_for_seller'    => $this->tipInvoiceRepository->getAllForSellerCount()
        ];
    }

    /**
     * @return array
     */
    public function getReviews() : array
    {
        return [
            'declined_reviews' => 0,
            'accepted_reviews' => 0,
            'reported_reviews' => 0,
            'pending_reviews'  => 0,
            'all_reviews'      => 0
        ];
    }

    /**
     * @return array
     */
    public function getChat() : array
    {
        return [
            'good_chats'       => 0,
            'suspicious_chats' => 0,
            'reported_chats'   => 0,
            'all_chats'        => 0
        ];
    }

    /**
     * @return array
     */
    public function getSupport() : array
    {
        return [
            'support_tickets' => 0,
            'finance_tickets' => 0,
            'report_tickets'  => 0,
            'dispute_tickets' => 0,
            'all_tickets'     => 0
        ];
    }

    //--------------------------------------------------------------------------
    // Clearing group cache functions

    /**
     * Suggestions cache releasing
     */
    public function releaseAllSuggestionCache() : void
    {
        $this->clearCache([
            'csauSuggestionRequests.all.count',
            'deviceSuggestionRequests.all.count'
        ]);
    }

    /**
     * Users cache releasing
     */
    public function releaseAllUserCache() : void
    {
        $this->clearCache([
            'users.all.count',
            'users.buyers.all.count',
            'users.sellers.all.count',
            'users.affiliates.all.count',
            'userProfileRequests.all.count'
        ]);
    }

    /**
     * Withdrawal cache releasing
     */
    public function releaseAllWithdrawalCache() : void
    {
        $this->clearCache([
            'withdrawalReceipts.all.count',
            'withdrawalRequests.all.count',
            'withdrawalRequests.buyers.all.count',
            'withdrawalRequests.sellers.all.count',
            'withdrawalRequests.affiliates.all.count'
        ]);
    }

    /**
     * Order cache releasing
     */
    public function releaseAllOrderCache() : void
    {
        $this->clearCache([
            'orders.all.count',
            'sales.all.count',
            'orderItems.all.count',
            'orderInvoices.buyers.all.count',
            'orderInvoices.sellers.all.count',
            'orderInvoices.affiliates.all.count'
        ]);
    }

    /**
     * Tip cache releasing
     */
    public function releaseAllTipCache() : void
    {
        $this->clearCache([
            'tips.all.count',
            'tipInvoices.buyers.all.count',
            'tipInvoices.sellers.all.count'
        ]);
    }

    //--------------------------------------------------------------------------
    // Clearing single cache functions

    /**
     * Add funds cache releasing
     */
    public function releaseAddFundsCache() : void
    {
        $this->clearCache([
            'addFundsReceipts.all.count'
        ]);
    }

    /**
     * New profile cache releasing
     */
    public function releaseProfileRequestCache() : void
    {
        $this->clearCache([
            'userProfileRequests.all.count'
        ]);
    }

    /**
     * User id verification request cache releasing
     */
    public function releaseIdVerificationRequestCache() : void
    {
        $this->clearCache([
            'userIdVerificationRequests.all.count'
        ]);
    }

    /**
     * User deactivation request cache releasing
     */
    public function releaseUserDeactivationRequestCache() : void
    {
        $this->clearCache([
            'userDeactivationRequests.all.count'
        ]);
    }

    /**
     * User unsuspension request cache releasing
     */
    public function releaseUserUnsuspensionRequestCache() : void
    {
        $this->clearCache([
            'userUnsuspendRequests.all.count'
        ]);
    }

    /**
     * User deletion request cache releasing
     */
    public function releaseUserDeletionRequestCache() : void
    {
        $this->clearCache([
            'userDeletionRequests.all.count'
        ]);
    }

    /**
     * Vybe publish request cache releasing
     */
    public function releaseVybePublishRequestCache() : void
    {
        $this->clearCache([
            'vybePublishRequests.all.count'
        ]);
    }

    /**
     * Vybe change request cache releasing
     */
    public function releaseVybeChangeRequestCache() : void
    {
        $this->clearCache([
            'vybeChangeRequests.all.count'
        ]);
    }

    /**
     * Vybe unpublish request cache releasing
     */
    public function releaseVybeUnpublishRequestCache() : void
    {
        $this->clearCache([
            'vybeUnpublishRequests.all.count'
        ]);
    }

    /**
     * Vybe unsuspend request cache releasing
     */
    public function releaseVybeUnsuspendRequestCache() : void
    {
        $this->clearCache([
            'vybeUnsuspendRequests.all.count'
        ]);
    }

    /**
     * Vybe deletion request cache releasing
     */
    public function releaseVybeDeletionRequestCache() : void
    {
        $this->clearCache([
            'vybeDeletionRequests.all.count'
        ]);
    }

    /**
     * Billing change request cache releasing
     */
    public function releaseBillingChangeRequestCache() : void
    {
        $this->clearCache([
            'billingChangeRequests.all.count',
            'billingChangeRequests.buyers.all.count',
            'billingChangeRequests.sellers.all.count',
            'billingChangeRequests.affiliates.all.count'
        ]);
    }

    /**
     * Payout method request cache releasing
     */
    public function releasePayoutMethodRequestCache() : void
    {
        $this->clearCache([
            'payoutMethodRequests.all.count',
            'payoutMethodRequests.buyers.all.count',
            'payoutMethodRequests.sellers.all.count',
            'payoutMethodRequests.affiliates.all.count'
        ]);
    }

    //--------------------------------------------------------------------------
    // Private methods

    /**
     * @param array $keys
     */
    private function clearCache(array $keys) : void
    {
        /** @var string $key */
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}