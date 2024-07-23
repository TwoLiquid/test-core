<?php

namespace App\Repositories\ExcludeTaxHistory\Interfaces;

use App\Models\MongoDb\User\Billing\ExcludeTaxHistory;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Admin\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VatNumberProofRepositoryInterface
 *
 * @package App\Repositories\VatNumberProof\Interfaces
 */
interface ExcludeTaxHistoryRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return ExcludeTaxHistory|null
     */
    public function findById(
        ?string $id
    ) : ?ExcludeTaxHistory;

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
     * This method provides creating row
     * with an eloquent model
     *
     * @param VatNumberProof $vatNumberProof
     * @param Admin $admin
     * @param bool $value
     *
     * @return ExcludeTaxHistory|null
     */
    public function store(
        VatNumberProof $vatNumberProof,
        Admin $admin,
        bool $value
    ) : ?ExcludeTaxHistory;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param ExcludeTaxHistory $excludeTaxHistory
     *
     * @return bool
     */
    public function delete(
        ExcludeTaxHistory $excludeTaxHistory
    ) : bool;
}
