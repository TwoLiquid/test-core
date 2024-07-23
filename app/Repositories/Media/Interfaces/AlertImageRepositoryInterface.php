<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Alert\Alert;
use App\Models\MySql\Media\AlertImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface AlertImageRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface AlertImageRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return AlertImage|null
     */
    public function findById(
        ?int $id
    ) : ?AlertImage;

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
     * with an eloquent model by query
     *
     * @param Alert $alert
     *
     * @return Collection
     */
    public function getByAlert(
        Alert $alert
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param Collection $alerts
     *
     * @return Collection
     */
    public function getByAlerts(
        Collection $alerts
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
