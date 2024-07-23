<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Media\ActivityImage;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\ActivityImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class ActivityImageRepository
 *
 * @package App\Repositories\Media
 */
class ActivityImageRepository extends BaseRepository implements ActivityImageRepositoryInterface
{
    /**
     * ActivityImageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.activityImage.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return ActivityImage|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?ActivityImage
    {
        try {
            return ActivityImage::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/activityImage.' . __FUNCTION__),
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
            return ActivityImage::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/activityImage.' . __FUNCTION__),
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
            return ActivityImage::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/activityImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByActivity(
        Activity $activity
    ) : Collection
    {
        try {
            return ActivityImage::query()
                ->where('activity_id', '=', $activity->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/activityImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $activities
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByActivities(
        Collection $activities
    ) : Collection
    {
        try {
            return ActivityImage::query()
                ->whereIn('activity_id', $activities->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/activityImage.' . __FUNCTION__),
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
            return ActivityImage::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/activityImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}