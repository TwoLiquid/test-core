<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserBackground;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\UserBackgroundRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserBackgroundRepository
 *
 * @package App\Repositories\Media
 */
class UserBackgroundRepository extends BaseRepository implements UserBackgroundRepositoryInterface
{
    /**
     * UserBackgroundRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userBackground.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserBackground|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserBackground
    {
        try {
            return UserBackground::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userBackground.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * 
     * @return UserBackground|null
     *
     * @throws DatabaseException
     */
    public function findByUser(
        User $user
    ) : ?UserBackground
    {
        try {
            return UserBackground::query()
                ->where('auth_id', '=', $user->auth_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userBackground.' . __FUNCTION__),
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
            return UserBackground::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userBackground.' . __FUNCTION__),
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
            return UserBackground::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userBackground.' . __FUNCTION__),
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
            return UserBackground::query()
                ->whereIn('id', $users->pluck('background_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userBackground.' . __FUNCTION__),
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
            return UserBackground::query()
                ->whereIn('id', $userProfileRequests->pluck('background_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userBackground.' . __FUNCTION__),
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
            return UserBackground::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userBackground.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}