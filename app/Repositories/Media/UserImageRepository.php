<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserImage;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\UserImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserImageRepository
 *
 * @package App\Repositories\Media
 */
class UserImageRepository extends BaseRepository implements UserImageRepositoryInterface
{
    /**
     * UserImageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userImage.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserImage|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserImage
    {
        try {
            return UserImage::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userImage.' . __FUNCTION__),
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
            return UserImage::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userImage.' . __FUNCTION__),
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
            return UserImage::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userImage.' . __FUNCTION__),
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
            return UserImage::query()
                ->where('auth_id', '=', $user->auth_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userImage.' . __FUNCTION__),
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
            return UserImage::query()
                ->whereIn('id', array_merge(
                        ...$users->filter(function ($item) {
                        return !is_null($item->images_ids);
                    })->pluck('images_ids')
                        ->values()
                        ->toArray()
                    )
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userImage.' . __FUNCTION__),
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
            return UserImage::query()
                ->whereIn('id', array_merge(
                        ...$userProfileRequests->filter(function ($item) {
                        return !is_null($item->images_ids);
                    })->pluck('images_ids')
                        ->values()
                        ->toArray()
                    )
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userImage.' . __FUNCTION__),
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
            return UserImage::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}