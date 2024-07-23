<?php

namespace App\Services\Billing\Interfaces;

use App\Models\MySql\Billing;

/**
 * Interface BillingServiceInterface
 *
 * @package App\Services\Billing\Interfaces
 */
interface BillingServiceInterface
{
    /**
     * @param Billing $billing
     * @param string|null $vatNumber
     *
     * @return bool
     */
    public function vatNumberHasChanges(
        Billing $billing,
        ?string $vatNumber
    ) : bool;
}