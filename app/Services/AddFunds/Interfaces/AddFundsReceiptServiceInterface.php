<?php

namespace App\Services\AddFunds\Interfaces;

use App\Models\MySql\Receipt\AddFundsReceipt;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface AddFundsReceiptServiceInterface
 *
 * @package App\Services\AddFunds\Interfaces
 */
interface AddFundsReceiptServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param AddFundsReceipt $addFundsReceipt
     * @param string $hash
     *
     * @return bool
     */
    public function checkHash(
        AddFundsReceipt $addFundsReceipt,
        string $hash
    ) : bool;

    /**
     * This method provides getting data
     * by related entity repository with a certain query
     *
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return string|null
     */
    public function getPaymentUrl(
        AddFundsReceipt $addFundsReceipt
    ) : ?string;

    /**
     * This method provides creating data
     *
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return AddFundsReceipt
     */
    public function executePayment(
        AddFundsReceipt $addFundsReceipt
    ) : AddFundsReceipt;

    /**
     * This method provides getting data
     *
     * @param Collection $addFundsReceipts
     *
     * @return Collection
     */
    public function getForAdminStatusesByIds(
        Collection $addFundsReceipts
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param AddFundsReceipt $addFundsReceipt
     * @param int $amount
     *
     * @return bool
     */
    public function isAvailableToAddPayment(
        AddFundsReceipt $addFundsReceipt,
        int $amount
    ) : bool;
}
