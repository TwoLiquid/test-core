<?php

namespace App\Transformers\Api\General\Profile\Home;

use App\Microservices\Media\Responses\UserImageResponse;
use App\Microservices\Media\Transformers\UserImageTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserImageLikeTransformer
 *
 * @package App\Transformers\Api\General\Profile\Home
 */
class UserImageLikeTransformer extends BaseTransformer
{
    /**
     * @var bool
     */
    protected bool $liked;

    /**
     * @var UserImageResponse
     */
    protected UserImageResponse $userImage;

    /**
     * UserImageLikeTransformer constructor
     *
     * @param bool $liked
     * @param UserImageResponse $userImage
     */
    public function __construct(
        bool $liked,
        UserImageResponse $userImage
    )
    {
        $this->liked = $liked;
        $this->userImage = $userImage;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'image'
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
    public function includeImage() : ?Item
    {
        return $this->item($this->userImage, new UserImageTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_image_like';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_image_likes';
    }
}
