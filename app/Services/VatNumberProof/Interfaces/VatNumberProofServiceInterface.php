<?php

namespace App\Services\VatNumberProof\Interfaces;

use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Billing;

/**
 * Interface VatNumberProofServiceInterface
 *
 * @package App\Services\VatNumberProof\Interfaces
 */
interface VatNumberProofServiceInterface
{
    /**
     * This method provides uploading data
     *
     * @param Billing $billing
     * @param VatNumberProof $vatNumberProof
     * @param array $filesItems
     */
    public function uploadFiles(
        Billing $billing,
        VatNumberProof $vatNumberProof,
        array $filesItems
    ) : void;
}