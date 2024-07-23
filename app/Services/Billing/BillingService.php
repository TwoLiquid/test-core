<?php

namespace App\Services\Billing;

use App\Models\MySql\Billing;
use App\Repositories\Billing\BillingRepository;
use App\Repositories\User\UserRepository;
use App\Services\Billing\Interfaces\BillingServiceInterface;

/**
 * Class BillingService
 *
 * @package App\Services\Billing
 */
class BillingService implements BillingServiceInterface
{
    /**
     * @var BillingRepository
     */
    protected BillingRepository $billingRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * BillingService constructor
     */
    public function __construct()
    {
        /** @var BillingRepository billingRepository */
        $this->billingRepository = new BillingRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param Billing $billing
     * @param string|null $vatNumber
     *
     * @return bool
     */
    public function vatNumberHasChanges(
        Billing $billing,
        ?string $vatNumber
    ) : bool
    {
        return $billing->vat_number != $vatNumber;
    }
}