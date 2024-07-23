<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\VybeVideo;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\VybeVideoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybeVideoRepository
 *
 * @package App\Repositories\Media
 */
class VybeVideoRepository extends BaseRepository implements VybeVideoRepositoryInterface
{
    /**
     * VybeVideoRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeVideo.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return VybeVideo|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?VybeVideo
    {
        try {
            return VybeVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideo.' . __FUNCTION__),
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
            return VybeVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideo.' . __FUNCTION__),
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
            return VybeVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideo.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByVybe(
        Vybe $vybe
    ) : Collection
    {
        try {
            return VybeVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->where('vybe_id', '=', $vybe->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideo.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $vybes
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByVybes(
        Collection $vybes
    ) : Collection
    {
        try {
            return VybeVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->whereIn('id', array_merge(
                        ...$vybes->filter(function ($item) {
                        return !is_null($item->videos_ids);
                    })->pluck('videos_ids')
                        ->values()
                        ->toArray()
                    )
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideo.' . __FUNCTION__),
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
            return VybeVideo::query()
                ->with([
                    'thumbnail'
                ])
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeVideo.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}