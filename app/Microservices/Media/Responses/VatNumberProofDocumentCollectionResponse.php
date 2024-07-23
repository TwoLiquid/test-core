<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class VatNumberProofDocumentCollectionResponse
 *
 * @property Collection $vatNumberProofDocuments
 *
 * @package App\Microservices\Media\Responses
 */
class VatNumberProofDocumentCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $vatNumberProofDocuments;

    /**
     * VatNumberProofDocumentCollectionResponse constructor
     *
     * @param array $vatNumberProofDocuments
     * @param string|null $message
     */
    public function __construct(
        array $vatNumberProofDocuments,
        ?string $message
    )
    {
        $this->vatNumberProofDocuments = new Collection();

        /** @var object $vatNumberProofDocument */
        foreach ($vatNumberProofDocuments as $vatNumberProofDocument) {
            $this->vatNumberProofDocuments->push(
                new VatNumberProofDocumentResponse(
                    $vatNumberProofDocument,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}