<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeVideoCollectionResponse
 *
 * @property Collection $videos
 *
 * @package App\Microservices\Media\Responses
 */
class VybeVideoCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $videos;

    /**
     * VybeVideoCollectionResponse constructor
     *
     * @param array $videos
     * @param string|null $message
     */
    public function __construct(
        array $videos,
        ?string $message
    )
    {
        $this->videos = new Collection();

        /** @var object $video */
        foreach ($videos as $video) {
            $this->videos->push(
                new VybeVideoResponse(
                    $video,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}