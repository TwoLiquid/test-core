<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Media\VybeVideo;
use App\Models\MySql\Media\VybeVideoThumbnail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeVideoThumbnailRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface VybeVideoThumbnailRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return VybeVideoThumbnail|null
     */
    public function findById(
        ?int $id
    ) : ?VybeVideoThumbnail;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param VybeVideo $vybeVideo
     *
     * @return VybeVideoThumbnail|null
     */
    public function findByVideo(
        VybeVideo $vybeVideo
    ) : ?VybeVideoThumbnail;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param Collection $videos
     *
     * @return Collection
     */
    public function getByVideos(
        Collection $videos
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $ids
     *
     * @return Collection
     */
    public function getByIds(
        array $ids
    ) : Collection;
}
