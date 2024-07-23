<?php

namespace App\Transformers\Api\General\Profile\Home;

use App\Microservices\Media\Responses\UserVideoResponse;
use App\Microservices\Media\Transformers\UserVideoTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserVideoLikeTransformer
 *
 * @package App\Transformers\Api\General\Profile\Home
 */
class UserVideoLikeTransformer extends BaseTransformer
{
    /**
     * @var bool
     */
    protected bool $liked;

    /**
     * @var UserVideoResponse
     */
    protected UserVideoResponse $userVideo;

    /**
     * UserVideoLikeTransformer constructor
     *
     * @param bool $liked
     * @param UserVideoResponse $userVideo
     */
    public function __construct(
        bool $liked,
        UserVideoResponse $userVideo
    )
    {
        $this->liked = $liked;
        $this->userVideo = $userVideo;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'video'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'liked' => $this->liked
        ];
    }

    /**
     * @return Item|null
     */
    public function includeVideo() : ?Item
    {
        return $this->item($this->userVideo, new UserVideoTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_video_like';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_video_likes';
    }
}
