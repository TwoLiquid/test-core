<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserVideoCollectionResponse
 *
 * @property Collection $videos
 *
 * @package App\Microservices\Media\Responses
 */
class UserVideoCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $videos;

    /**
     * UserVideoCollectionResponse constructor
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
                new UserVideoResponse(
                    $video,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}