<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class WithdrawalReceiptProofImageCollectionResponse
 *
 * @property Collection $withdrawalReceiptProofImages
 *
 * @package App\Microservices\Media\Responses
 */
class WithdrawalReceiptProofImageCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $withdrawalReceiptProofImages;

    /**
     * WithdrawalReceiptProofImageCollectionResponse constructor
     *
     * @param array $withdrawalReceiptProofImages
     * @param string|null $message
     */
    public function __construct(
        array $withdrawalReceiptProofImages,
        ?string $message
    )
    {
        $this->withdrawalReceiptProofImages = new Collection();

        /** @var object $withdrawalReceiptProofImage */
        foreach ($withdrawalReceiptProofImages as $withdrawalReceiptProofImage) {
            $this->withdrawalReceiptProofImages->push(
                new WithdrawalReceiptProofImageResponse(
                    $withdrawalReceiptProofImage,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}