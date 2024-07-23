<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserVideo;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\UserVideoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserVideoRepository
 *
 * @package App\Repositories\Media
 */
class UserVideoRepository extends BaseRepository implements UserVideoRepositoryInterface
{
    /**
     * UserVideoRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userVideo.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserVideo|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserVideo
    {
        try {
            return UserVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideo.' . __FUNCTION__),
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
            return UserVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideo.' . __FUNCTION__),
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
            return UserVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideo.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByUser(
        User $user
    ) : Collection
    {
        try {
            return UserVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->where('auth_id', '=', $user->auth_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideo.' . __FUNCTION__),
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
            return UserVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->whereIn('id', array_merge(
                        ...$users->filter(function ($item) {
                        return !is_null($item->videos_ids);
                    })->pluck('videos_ids')
                        ->values()
                        ->toArray()
                    )
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideo.' . __FUNCTION__),
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
            return UserVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->whereIn('id', array_merge(
                        ...$userProfileRequests->filter(function ($item) {
                        return !is_null($item->videos_ids);
                    })->pluck('videos_ids')
                        ->values()
                        ->toArray()
                    )
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideo.' . __FUNCTION__),
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
            return UserVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideo.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}