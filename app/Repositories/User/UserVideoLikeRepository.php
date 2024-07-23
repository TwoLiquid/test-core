<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\User\Media\UserVideoLike;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserVideoLikeRepositoryInterface;
use Exception;

/**
 * Class UserVideoLikeRepository
 *
 * @package App\Repositories\User
 */
class UserVideoLikeRepository extends BaseRepository implements UserVideoLikeRepositoryInterface
{
    /**
     * UserVideoLikeRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userVideoLike.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return UserVideoLike|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?UserVideoLike
    {
        try {
            return UserVideoLike::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userVideoLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int $videoId
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getLikesForVideoCount(
        int $videoId
    ) : int
    {
        try {
            return UserVideoLike::query()
                ->where('video_id', '=', $videoId)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userVideoLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $videoId
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForVideo(
        User $user,
        int $videoId
    ) : bool
    {
        try {
            return UserVideoLike::query()
                ->where('user_id', '=', $user->id)
                ->where('video_id', '=', $videoId)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userVideoLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $videoId
     *
     * @return UserVideoLike|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        int $videoId
    ) : ?UserVideoLike
    {
        try {
            return UserVideoLike::query()->create([
                'user_id'  => $user->id,
                'video_id' => $videoId
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userVideoLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $videoId
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForVideo(
        User $user,
        int $videoId
    ) : bool
    {
        try {
            return UserVideoLike::query()
                ->where('user_id', '=', $user->id)
                ->where('video_id', '=', $videoId)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userVideoLike.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
