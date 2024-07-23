<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MongoDb\Vybe\VybeStatusHistory;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeStatusHistoryRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeStatusHistoryRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeStatusHistory|null
     */
    public function findById(
        ?string $id
    ) : ?VybeStatusHistory;

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
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return Collection
     */
    public function getAllForVybe(
        Vybe $vybe
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param VybeStatusListItem $vybeTypeListItem
     *
     * @return VybeStatusHistory|null
     */
    public function store(
        Vybe $vybe,
        VybeStatusListItem $vybeTypeListItem
    ) : ?VybeStatusHistory;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeStatusHistory $vybeStatusHistory
     * @param Vybe|null $vybe
     * @param VybeStatusListItem|null $vybeTypeListItem
     *
     * @return VybeStatusHistory
     */
    public function update(
        VybeStatusHistory $vybeStatusHistory,
        ?Vybe $vybe,
        ?VybeStatusListItem $vybeTypeListItem
    ) : VybeStatusHistory;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeStatusHistory $vybeStatusHistory
     *
     * @return bool
     */
    public function delete(
        VybeStatusHistory $vybeStatusHistory
    ) : bool;
}
