<?php

namespace App\Services\Log\Interfaces;

use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Microservices\Log\Responses\UserWalletTransactionLogResponse;
use App\Models\MongoDb\WithdrawalRequest;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\Tip\TipInvoice;
use App\Models\MySql\User\UserBalance;

/**
 * Interface LogServiceInterface
 *
 * @package App\Services\Log\Interfaces
 */
interface LogServiceInterface
{
    /**
     * This method provides creating data
     *
     * @param AddFundsReceipt $addFundsReceipt
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addFundsReceiptLog(
        AddFundsReceipt $addFundsReceipt,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param OrderInvoice $orderInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addInvoiceForBuyerLog(
        OrderInvoice $orderInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param Sale $sale
     * @param OrderInvoice $orderInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addInvoiceForSellerLog(
        Sale $sale,
        OrderInvoice $orderInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param OrderInvoice $creditInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addCreditInvoiceForBuyerLog(
        OrderInvoice $creditInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param OrderInvoice $creditInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addCreditInvoiceForSellerLog(
        OrderInvoice $creditInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param TipInvoice $tipInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addTipInvoiceForBuyerLog(
        TipInvoice $tipInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param TipInvoice $tipInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addTipInvoiceForSellerLog(
        TipInvoice $tipInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param Order $order
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addOrderOverviewLog(
        Order $order,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param Sale $sale
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addSaleOverviewLog(
        Sale $sale,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param WithdrawalRequest $withdrawalRequest
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addWithdrawalRequestLog(
        WithdrawalRequest $withdrawalRequest,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;

    /**
     * This method provides creating data
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     */
    public function addWithdrawalReceiptLog(
        WithdrawalReceipt $withdrawalReceipt,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse;
}