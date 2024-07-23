<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\VybeImage;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\VybeImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybeImageRepository
 *
 * @package App\Repositories\Media
 */
class VybeImageRepository extends BaseRepository implements VybeImageRepositoryInterface
{
    /**
     * VybeImageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeImage.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return VybeImage|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?VybeImage
    {
        try {
            return VybeImage::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeImage.' . __FUNCTION__),
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
            return VybeImage::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeImage.' . __FUNCTION__),
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
            return VybeImage::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeImage.' . __FUNCTION__),
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
            return VybeImage::query()
                ->where('vybe_id', '=', $vybe->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeImage.' . __FUNCTION__),
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
            return VybeImage::query()
                ->whereIn('id', array_merge(
                    ...$vybes->filter(function ($item) {
                        return !is_null($item->images_ids);
                    })->pluck('images_ids')
                        ->values()
                        ->toArray()
                    )
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeImage.' . __FUNCTION__),
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
            return VybeImage::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vybeImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}