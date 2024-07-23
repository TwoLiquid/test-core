<?php

namespace App\Services\Withdrawal\Interfaces;

use App\Models\MySql\Receipt\Transaction\WithdrawalTransaction;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface WithdrawalReceiptServiceInterface
 *
 * @package App\Services\Withdrawal\Interfaces
 */
interface WithdrawalReceiptServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param int $amount
     *
     * @return bool
     */
    public function isAvailableToAddTransfer(
        WithdrawalReceipt $withdrawalReceipt,
        int $amount
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param int $amount
     *
     * @return WithdrawalReceipt
     */
    public function updateTotalPaid(
        WithdrawalReceipt $withdrawalReceipt,
        int $amount
    ) : WithdrawalReceipt;

    /**
     * This method provides updating data
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param WithdrawalTransaction $withdrawalTransaction
     *
     * @return WithdrawalReceipt
     */
    public function addAmountByTransfer(
        WithdrawalReceipt $withdrawalReceipt,
        WithdrawalTransaction $withdrawalTransaction
    ) : WithdrawalReceipt;

    /**
     * This method provides uploading data
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param array $filesItems
     */
    public function uploadProofFiles(
        WithdrawalReceipt $withdrawalReceipt,
        array $filesItems
    ) : void;

    /**
     * This method provides getting data
     *
     * @param Collection $withdrawalReceipts
     *
     * @return Collection
     */
    public function getForAdminStatusesByIds(
        Collection $withdrawalReceipts
    ) : Collection;
}