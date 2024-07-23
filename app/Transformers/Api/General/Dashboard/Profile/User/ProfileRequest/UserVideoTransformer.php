<?php

namespace App\Transformers\Api\General\Dashboard\Profile\User\ProfileRequest;

use App\Models\MySql\Media\UserVideo;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserVideoTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile\User\ProfileRequest
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
     * @param UserVideo $userVideo
     *
     * @return array
     */
    public function transform(UserVideo $userVideo) : array
    {
        return [
            'id'       => $userVideo->id,
            'url'      => $userVideo->url,
            'duration' => $userVideo->duration,
            'mime'     => $userVideo->mime,
            'declined' => $userVideo->declined
        ];
    }

    /**
     * @param UserVideo $userVideo
     *
     * @return Item|null
     */
    public function includeThumbnail(UserVideo $userVideo): ?Item
    {
        $thumbnail = null;

        if ($userVideo->relationLoaded('thumbnail')) {
            $thumbnail = $userVideo->thumbnail;
        }

        return $thumbnail ? $this->item($thumbnail, new UserVideoThumbnailTransformer) : null;
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
