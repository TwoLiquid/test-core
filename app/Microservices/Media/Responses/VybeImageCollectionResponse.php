<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeImageCollectionResponse
 *
 * @property Collection $images
 *
 * @package App\Microservices\Media\Responses
 */
class VybeImageCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $images;

    /**
     * VybeImageCollectionResponse constructor
     *
     * @param array $images
     * @param string|null $message
     */
    public function __construct(
        array $images,
        ?string $message
    )
    {
        $this->images = new Collection();

        /** @var object $image */
        foreach ($images as $image) {
            $this->images->push(
                new VybeImageResponse(
                    $image,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}