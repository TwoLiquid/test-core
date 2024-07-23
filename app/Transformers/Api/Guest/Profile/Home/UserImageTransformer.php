<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserImage;
use App\Models\MySql\User\User;
use App\Repositories\User\UserImageLikeRepository;
use App\Transformers\BaseTransformer;

/**
 * Class UserImageTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class UserImageTransformer extends BaseTransformer
{
    /**
     * @var User|null
     */
    protected ?User $authUser;

    /**
     * @var UserImageLikeRepository
     */
    protected UserImageLikeRepository $userImageLikeRepository;

    /**
     * UserImageTransformer constructor
     *
     * @param User|null $authUser
     */
    public function __construct(
        ?User $authUser = null
    )
    {
        /** @var User authUser */
        $this->authUser = $authUser;

        /** @var UserImageLikeRepository userImageLikeRepository */
        $this->userImageLikeRepository = new UserImageLikeRepository();
    }

    /**
     * @param UserImage $userImage
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function transform(UserImage $userImage) : array
    {
        return [
            'id'          => $userImage->id,
            'url'         => $userImage->url,
            'url_min'     => $userImage->url_min,
            'mime'        => $userImage->mime,
            'declined'    => $userImage->declined,
            'is_favorite' => $this->authUser && $this->userImageLikeRepository->existsForImage(
                $this->authUser,
                $userImage->id
            ),
            'likes'       => $this->userImageLikeRepository->getLikesForImageCount(
                $userImage->id
            )
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_images';
    }
}
