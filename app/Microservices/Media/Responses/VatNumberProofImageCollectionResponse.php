<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class VatNumberProofImageCollectionResponse
 *
 * @property Collection $vatNumberProofImages
 *
 * @package App\Microservices\Media\Responses
 */
class VatNumberProofImageCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $vatNumberProofImages;

    /**
     * VatNumberProofImageCollectionResponse constructor
     *
     * @param array $vatNumberProofImages
     * @param string|null $message
     */
    public function __construct(
        array $vatNumberProofImages,
        ?string $message
    )
    {
        $this->vatNumberProofImages = new Collection();

        /** @var object $vatNumberProofImage */
        foreach ($vatNumberProofImages as $vatNumberProofImage) {
            $this->vatNumberProofImages->push(
                new VatNumberProofImageResponse(
                    $vatNumberProofImage,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}