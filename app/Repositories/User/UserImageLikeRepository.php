<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\User\Media\UserImageLike;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserImageLikeRepositoryInterface;
use Exception;

/**
 * Class UserImageLikeRepository
 *
 * @package App\Repositories\User
 */
class UserImageLikeRepository extends BaseRepository implements UserImageLikeRepositoryInterface
{
    /**
     * UserImageLikeRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userImageLike.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return UserImageLike|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?UserImageLike
    {
        try {
            return UserImageLike::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userImageLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int $imageId
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getLikesForImageCount(
        int $imageId
    ) : int
    {
        try {
            return UserImageLike::query()
                ->where('image_id', '=', $imageId)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userImageLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $imageId
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForImage(
        User $user,
        int $imageId
    ) : bool
    {
        try {
            return UserImageLike::query()
                ->where('user_id', '=', $user->id)
                ->where('image_id', '=', $imageId)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userImageLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $imageId
     *
     * @return UserImageLike|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        int $imageId
    ) : ?UserImageLike
    {
        try {
            return UserImageLike::query()->create([
                'user_id'  => $user->id,
                'image_id' => $imageId
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userImageLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $imageId
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForImage(
        User $user,
        int $imageId
    ) : bool
    {
        try {
            return UserImageLike::query()
                ->where('user_id', '=', $user->id)
                ->where('image_id', '=', $imageId)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userImageLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
