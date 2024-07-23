<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserVideo;
use App\Models\MySql\User\User;
use App\Repositories\User\UserVideoLikeRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserVideoTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class UserVideoTransformer extends BaseTransformer
{
    /**
     * @var User|null
     */
    protected ?User $authUser;

    /**
     * @var UserVideoLikeRepository
     */
    protected UserVideoLikeRepository $userVideoLikeRepository;

    /**
     * @param User|null $authUser
     */
    public function __construct(
        ?User $authUser = null
    )
    {
        /** @var User authUser */
        $this->authUser = $authUser;

        /** @var UserVideoLikeRepository userVideoLikeRepository */
        $this->userVideoLikeRepository = new UserVideoLikeRepository();
    }

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
     *
     * @throws DatabaseException
     */
    public function transform(UserVideo $userVideo) : array
    {
        return [
            'id'          => $userVideo->id,
            'url'         => $userVideo->url,
            'duration'    => $userVideo->duration,
            'mime'        => $userVideo->mime,
            'declined'    => $userVideo->declined,
            'is_favorite' => $this->authUser && $this->userVideoLikeRepository->existsForVideo(
                    $this->authUser,
                    $userVideo->id
                ),
            'likes'       => $this->userVideoLikeRepository->getLikesForVideoCount(
                $userVideo->id
            )
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
