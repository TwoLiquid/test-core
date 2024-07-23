<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\PlatformIcon;
use App\Models\MySql\Platform;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\PlatformIconRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PlatformIconRepository
 *
 * @package App\Repositories\Media
 */
class PlatformIconRepository extends BaseRepository implements PlatformIconRepositoryInterface
{
    /**
     * PlatformIconRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.platformIcon.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PlatformIcon|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PlatformIcon
    {
        try {
            return PlatformIcon::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/platformIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     *
     * @return PlatformIcon|null
     *
     * @throws DatabaseException
     */
    public function findByPlatform(
        Platform $platform
    ) : ?PlatformIcon
    {
        try {
            return PlatformIcon::query()
                ->where('platform_id', '=', $platform->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/platformIcon.' . __FUNCTION__),
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
            return PlatformIcon::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/platformIcon.' . __FUNCTION__),
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
            return PlatformIcon::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/platformIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $platforms
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByPlatforms(
        Collection $platforms
    ) : Collection
    {
        try {
            return PlatformIcon::query()
                ->whereIn('platform_id', $platforms->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/platformIcon.' . __FUNCTION__),
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
            return PlatformIcon::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/platformIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}