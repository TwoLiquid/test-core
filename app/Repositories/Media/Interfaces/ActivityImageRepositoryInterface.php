<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Media\ActivityImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ActivityImageRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface ActivityImageRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return ActivityImage|null
     */
    public function findById(
        ?int $id
    ) : ?ActivityImage;

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
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Activity $activity
     *
     * @return Collection
     */
    public function getByActivity(
        Activity $activity
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Collection $activities
     *
     * @return Collection
     */
    public function getByActivities(
        Collection $activities
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
