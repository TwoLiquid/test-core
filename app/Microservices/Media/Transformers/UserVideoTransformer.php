<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\UserVideoResponse;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserVideoTransformer
 *
 * @package App\Transformers\Api
 */
class UserVideoTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'thumbnail'
    ];

    /**
     * @param UserVideoResponse $userVideo
     *
     * @return array
     */
    public function transform(UserVideoResponse $userVideo) : array
    {
        return [
            'id'         => $userVideo->id,
            'auth_id'    => $userVideo->authId,
            'request_id' => $userVideo->requestId,
            'url'        => $userVideo->url,
            'duration'   => $userVideo->duration,
            'mime'       => $userVideo->mime,
            'declined'   => $userVideo->declined,
            'likes'      => $userVideo->likes
        ];
    }

    /**
     * @param UserVideoResponse $userVideo
     *
     * @return Item|null
     */
    public function includeThumbnail(UserVideoResponse $userVideo): ?Item
    {
        return isset($userVideo->thumbnail) ?
            $this->item($userVideo->thumbnail, new UserVideoThumbnailTransformer) :
            null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_video';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_videos';
    }
}
