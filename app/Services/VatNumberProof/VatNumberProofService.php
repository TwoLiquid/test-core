<?php

namespace App\Services\VatNumberProof;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\MediaMicroservice;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Billing;
use App\Repositories\VatNumberProof\VatNumberProofRepository;
use App\Services\VatNumberProof\Interfaces\VatNumberProofServiceInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class VatNumberProofService
 *
 * @package App\Services\Notification
 */
class VatNumberProofService implements VatNumberProofServiceInterface
{
    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var VatNumberProofRepository
     */
    protected VatNumberProofRepository $vatNumberProofRepository;

    /**
     * VatNumberProofService constructor
     */
    public function __construct()
    {
        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var VatNumberProofRepository vatNumberProofRepository */
        $this->vatNumberProofRepository = new VatNumberProofRepository();
    }

    /**
     * @param Billing $billing
     * @param VatNumberProof $vatNumberProof
     * @param array $filesItems
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadFiles(
        Billing $billing,
        VatNumberProof $vatNumberProof,
        array $filesItems
    ) : void
    {
        $images = [];
        $documents = [];

        /** @var array $fileItem */
        foreach ($filesItems as $fileItem) {
            if (in_array(
                trim($fileItem['mime']),
                config('media.default.image.allowedMimes')
            )) {
                $images[] = $fileItem;
            } else {
                $documents[] = $fileItem;
            }
        }

        /**
         * Checking images existence
         */
        if (!empty($images)) {

            /**
             * Storing vat number proof images
             */
            $this->mediaMicroservice->storeVatNumberProofImages(
                $vatNumberProof,
                $images
            );
        }

        /**
         * Checking documents existence
         */
        if (!empty($documents)) {

            /**
             * Storing vat number proof documents
             */
            $this->mediaMicroservice->storeVatNumberProofDocuments(
                $vatNumberProof,
                $documents
            );
        }
    }
}