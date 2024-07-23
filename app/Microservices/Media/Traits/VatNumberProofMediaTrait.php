<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\VatNumberProofDocumentCollectionResponse;
use App\Microservices\Media\Responses\VatNumberProofImageCollectionResponse;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait VatNumberProofMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait VatNumberProofMediaTrait
{
    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForVatNumberProof(
        VatNumberProof $vatNumberProof
    ) : array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/vat/number/proof/' . $vatNumberProof->_id
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vatNumberProof.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vatNumberProofsIds
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForVatNumberProofs(
        array $vatNumberProofsIds
    ) : array
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/vat/number/proofs', [
                    'form_params' => [
                        'vat_number_proofs_ids' => $vatNumberProofsIds
                    ]
                ]
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vatNumberProof.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     * @param array $imagesArray
     *
     * @return VatNumberProofImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeVatNumberProofImages(
        VatNumberProof $vatNumberProof,
        array $imagesArray
    ) : VatNumberProofImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/vat/number/proof/' . $vatNumberProof->_id . '/images', [
                'form_params' => [
                    'vat_number_proof_images' => $imagesArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VatNumberProofImageCollectionResponse(
                $responseData->vat_number_proof_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vatNumberProof.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     * @param array $documentsArray
     *
     * @return VatNumberProofDocumentCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeVatNumberProofDocuments(
        VatNumberProof $vatNumberProof,
        array $documentsArray
    ) : VatNumberProofDocumentCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/vat/number/proof/' . $vatNumberProof->_id . '/documents', [
                    'form_params' => [
                        'vat_number_proof_documents' => $documentsArray
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VatNumberProofDocumentCollectionResponse(
                $responseData->vat_number_proof_documents,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vatNumberProof.' . __FUNCTION__)
            );
        }
    }
}