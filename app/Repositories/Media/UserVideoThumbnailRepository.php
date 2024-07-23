<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserVideo;
use App\Models\MySql\Media\UserVideoThumbnail;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\UserVideoThumbnailRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserVideoThumbnailRepository
 *
 * @package App\Repositories\Media
 */
class UserVideoThumbnailRepository extends BaseRepository implements UserVideoThumbnailRepositoryInterface
{
    /**
     * UserVideoThumbnailRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userVideoThumbnail.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserVideoThumbnail|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserVideoThumbnail
    {
        try {
            return UserVideoThumbnail::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideoThumbnail.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserVideo $userVideo
     *
     * @return UserVideoThumbnail|null
     *
     * @throws DatabaseException
     */
    public function findByVideo(
        UserVideo $userVideo
    ) : ?UserVideoThumbnail
    {
        try {
            return UserVideoThumbnail::query()
                ->where('video_id', '=', $userVideo->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideoThumbnail.' . __FUNCTION__),
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
            return UserVideoThumbnail::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideoThumbnail.' . __FUNCTION__),
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
            return UserVideoThumbnail::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideoThumbnail.' . __FUNCTION__),
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
            return UserVideoThumbnail::query()
                ->whereIn('video_id', $videos->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideoThumbnail.' . __FUNCTION__),
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
            return UserVideoThumbnail::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVideoThumbnail.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}