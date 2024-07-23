<?php

namespace App\Services\User\Interfaces;

use App\Models\MySql\User\User;
use App\Models\MySql\User\UserBalance;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserBalanceServiceInterface
 *
 * @package App\Services\User\Interfaces
 */
interface UserBalanceServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param User $user
     * @param float $amount
     *
     * @return bool
     */
    public function isBuyerBalanceEnough(
        User $user,
        float $amount
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection $withdrawalRequests
     *
     * @return Collection
     */
    public function getTypesCountsByWithdrawalRequestsIds(
        Collection $withdrawalRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $payoutMethodRequests
     *
     * @return Collection
     */
    public function getTypesCountsByPayoutMethodRequestsIds(
        Collection $payoutMethodRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $billingChangeRequests
     *
     * @return Collection
     */
    public function getTypesCountsByBillingChangeRequestsIds(
        Collection $billingChangeRequests
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param UserBalance $userBalance
     * @param float $amount
     * @param bool $decrease
     * @param bool $pending
     *
     * @return UserBalance
     */
    public function change(
        UserBalance $userBalance,
        float $amount,
        bool $decrease,
        bool $pending
    ) : UserBalance;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param float $amount
     *
     * @return UserBalance
     */
    public function pendingPayout(
        User $user,
        float $amount
    ) : UserBalance;
}