<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\WithdrawalReceiptProofDocumentCollectionResponse;
use App\Microservices\Media\Responses\WithdrawalReceiptProofImageCollectionResponse;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait WithdrawalReceiptProofMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait WithdrawalReceiptProofMediaTrait
{
    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForWithdrawalReceipt(
        WithdrawalReceipt $withdrawalReceipt
    ) : array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/withdrawal/receipt/' . $withdrawalReceipt->id . '/proofs'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/withdrawalReceiptProof.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $withdrawalReceiptsIds
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForWithdrawalReceipts(
        array $withdrawalReceiptsIds
    ) : array
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/withdrawal/receipts/proofs', [
                    'form_params' => [
                        'withdrawal_receipts_ids' => $withdrawalReceiptsIds
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
                trans('exceptions/microservice/media/withdrawalReceiptProof.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param array $imagesArray
     *
     * @return WithdrawalReceiptProofImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeWithdrawalReceiptProofImages(
        WithdrawalReceipt $withdrawalReceipt,
        array $imagesArray
    ) : WithdrawalReceiptProofImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/withdrawal/receipt/' . $withdrawalReceipt->id . '/proof/images', [
                'form_params' => [
                    'withdrawal_receipt_proof_images' => $imagesArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new WithdrawalReceiptProofImageCollectionResponse(
                $responseData->withdrawal_receipt_proof_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/withdrawalReceiptProof.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param array $documentsArray
     * 
     * @return WithdrawalReceiptProofDocumentCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeWithdrawalReceiptProofDocuments(
        WithdrawalReceipt $withdrawalReceipt,
        array $documentsArray
    ) : WithdrawalReceiptProofDocumentCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/withdrawal/receipt/' . $withdrawalReceipt->id . '/proof/documents', [
                    'form_params' => [
                        'withdrawal_receipt_proof_documents' => $documentsArray
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new WithdrawalReceiptProofDocumentCollectionResponse(
                $responseData->withdrawal_receipt_proof_documents,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/withdrawalReceiptProof.' . __FUNCTION__)
            );
        }
    }
}