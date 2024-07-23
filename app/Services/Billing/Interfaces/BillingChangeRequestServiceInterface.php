<?php

namespace App\Services\Billing\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface BillingChangeRequestServiceInterface
 *
 * @package App\Services\Billing\Interfaces
 */
interface BillingChangeRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $billingChangeRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $billingChangeRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @return Collection
     */
    public function getUserBalanceTypesWithCounts() : Collection;
}