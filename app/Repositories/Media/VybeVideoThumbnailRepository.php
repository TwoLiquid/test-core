<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\VybeVideo;
use App\Models\MySql\Media\VybeVideoThumbnail;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\VybeVideoThumbnailRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybeVideoThumbnailRepository
 * 
 * @package App\Repositories\Media
 */
class VybeVideoThumbnailRepository extends BaseRepository implements VybeVideoThumbnailRepositoryInterface
{
    /**
     * VybeVideoThumbnailRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeVideoThumbnail.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return VybeVideoThumbnail|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?VybeVideoThumbnail
    {
        try {
            return VybeVideoThumbnail::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideoThumbnail.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeVideo $vybeVideo
     *
     * @return VybeVideoThumbnail|null
     *
     * @throws DatabaseException
     */
    public function findByVideo(
        VybeVideo $vybeVideo
    ) : ?VybeVideoThumbnail
    {
        try {
            return VybeVideoThumbnail::query()
                ->where('video_id', '=', $vybeVideo->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideoThumbnail.' . __FUNCTION__),
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
            return VybeVideoThumbnail::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideoThumbnail.' . __FUNCTION__),
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
            return VybeVideoThumbnail::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideoThumbnail.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $videos
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByVideos(
        Collection $videos
    ) : Collection
    {
        try {
            return VybeVideoThumbnail::query()
                ->whereIn('video_id', $videos->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideoThumbnail.' . __FUNCTION__),
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
            return VybeVideoThumbnail::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideoThumbnail.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}