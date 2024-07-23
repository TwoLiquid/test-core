<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserAvatar;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\UserAvatarRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserAvatarRepository
 *
 * @package App\Repositories\Media
 */
class UserAvatarRepository extends BaseRepository implements UserAvatarRepositoryInterface
{
    /**
     * UserAvatarRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userAvatar.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserAvatar|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserAvatar
    {
        try {
            return UserAvatar::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * 
     * @return UserAvatar|null
     *
     * @throws DatabaseException
     */
    public function findByUser(
        User $user
    ) : ?UserAvatar
    {
        try {
            return UserAvatar::query()
                ->where('auth_id', '=', $user->auth_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return UserAvatar::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return UserAvatar::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $users
     * 
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByUsers(
        Collection $users
    ) : Collection
    {
        try {
            return UserAvatar::query()
                ->whereIn('id', $users->pluck('avatar_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $userProfileRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByRequests(
        Collection $userProfileRequests
    ) : Collection
    {
        try {
            return UserAvatar::query()
                ->whereIn('id', $userProfileRequests->pluck('avatar_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ids
    ) : Collection
    {
        try {
            return UserAvatar::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}