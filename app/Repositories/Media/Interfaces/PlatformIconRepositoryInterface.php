<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Media\PlatformIcon;
use App\Models\MySql\Platform;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PlatformIconRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface PlatformIconRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PlatformIcon|null
     */
    public function findById(
        ?int $id
    ) : ?PlatformIcon;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param Platform $platform
     *
     * @return PlatformIcon|null
     */
    public function findByPlatform(
        Platform $platform
    ) : ?PlatformIcon;

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
     * @param Collection $platforms
     *
     * @return Collection
     */
    public function getByPlatforms(
        Collection $platforms
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
