<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserImageCollectionResponse
 *
 * @property Collection $images
 *
 * @package App\Microservices\Media\Responses
 */
class UserImageCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $images;

    /**
     * UserImageCollectionResponse constructor
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
                new UserImageResponse(
                    $image,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}