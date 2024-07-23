<?php

namespace App\Services\Withdrawal\Interfaces;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface WithdrawalRequestServiceInterface
 *
 * @package App\Services\Withdrawal\Interfaces
 */
interface WithdrawalRequestServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param User $user
     * @param int $amount
     *
     * @return bool
     */
    public function isAvailableForWithdrawal(
        User $user,
        int $amount
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection|null $withdrawalRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $withdrawalRequests
    ) : Collection;
}