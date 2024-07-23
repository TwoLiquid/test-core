<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertImageCollectionResponse
 *
 * @property Collection $images
 *
 * @package App\Microservices\Media\Responses
 */
class AlertImageCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $images;

    /**
     * AlertImageCollectionResponse constructor
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
                new AlertImageResponse(
                    $image,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}