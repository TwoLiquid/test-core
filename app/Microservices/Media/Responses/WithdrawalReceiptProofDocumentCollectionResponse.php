<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class WithdrawalReceiptProofDocumentCollectionResponse
 *
 * @property Collection $withdrawalReceiptProofDocuments
 *
 * @package App\Microservices\Media\Responses
 */
class WithdrawalReceiptProofDocumentCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $withdrawalReceiptProofDocuments;

    /**
     * WithdrawalReceiptProofDocumentCollectionResponse constructor
     *
     * @param array $withdrawalReceiptProofDocuments
     * @param string|null $message
     */
    public function __construct(
        array $withdrawalReceiptProofDocuments,
        ?string $message
    )
    {
        $this->withdrawalReceiptProofDocuments = new Collection();

        /** @var object $withdrawalReceiptProofDocument */
        foreach ($withdrawalReceiptProofDocuments as $withdrawalReceiptProofDocument) {
            $this->withdrawalReceiptProofDocuments->push(
                new WithdrawalReceiptProofDocumentResponse(
                    $withdrawalReceiptProofDocument,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}