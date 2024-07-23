<?php

namespace App\Services\Payout\Interfaces;

use App\Models\MongoDb\Payout\PayoutMethodRequest;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PayoutMethodRequestServiceInterface
 *
 * @package App\Services\Payout\Interfaces
 */
interface PayoutMethodRequestServiceInterface
{
    /**
     * This method provides creating data
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param array $fields
     *
     * @return Collection
     */
    public function createFields(
        PayoutMethodRequest $payoutMethodRequest,
        array $fields
    ) : Collection;

    /**
     * This method provides creating data
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     */
    public function executeRequest(
        PayoutMethodRequest $payoutMethodRequest
    ) : void;

    /**
     * This method provides getting data
     *
     * @param Collection|null $payoutMethodRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $payoutMethodRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @return Collection
     */
    public function getUserBalanceTypesWithCounts() : Collection;
}