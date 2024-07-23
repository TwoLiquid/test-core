<?php

namespace App\Repositories\VatNumberProof\Interfaces;

use App\Lists\VatNumberProof\Status\VatNumberProofStatusListItem;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Billing;
use App\Models\MySql\Place\CountryPlace;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VatNumberProofRepositoryInterface
 *
 * @package App\Repositories\VatNumberProof\Interfaces
 */
interface VatNumberProofRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VatNumberProof|null
     */
    public function findById(
        ?string $id
    ) : ?VatNumberProof;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VatNumberProof|null
     */
    public function findFullById(
        ?string $id
    ) : ?VatNumberProof;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Billing $billing
     *
     * @return VatNumberProof|null
     */
    public function findByBilling(
        Billing $billing
    ) : ?VatNumberProof;

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
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param Billing $billing
     *
     * @return Collection
     */
    public function getByBilling(
        Billing $billing
    ) : Collection;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param Billing $billing
     * @param CountryPlace $countryPlace
     * @param string $companyName
     * @param string $vatNumber
     * @param Admin $admin
     *
     * @return VatNumberProof|null
     */
    public function store(
        Billing $billing,
        CountryPlace $countryPlace,
        string $companyName,
        string $vatNumber,
        Admin $admin
    ) : ?VatNumberProof;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VatNumberProof $vatNumberProof
     * @param VatNumberProofStatusListItem|null $vatNumberProofStatusListItem
     * @param bool|null $excludeTax
     * @param string|null $excludeTaxDate
     * @param string|null $statusChangeDate
     *
     * @return VatNumberProof|null
     */
    public function update(
        VatNumberProof $vatNumberProof,
        ?VatNumberProofStatusListItem $vatNumberProofStatusListItem,
        ?bool $excludeTax,
        ?string $excludeTaxDate,
        ?string $statusChangeDate
    ) : ?VatNumberProof;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VatNumberProof $vatNumberProof
     * @param VatNumberProofStatusListItem $vatNumberProofStatusListItem
     *
     * @return VatNumberProof|null
     */
    public function updateStatus(
        VatNumberProof $vatNumberProof,
        VatNumberProofStatusListItem $vatNumberProofStatusListItem
    ) : ?VatNumberProof;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VatNumberProof $vatNumberProof
     * @param bool $excludeTax
     *
     * @return VatNumberProof|null
     */
    public function updateExcludeTax(
        VatNumberProof $vatNumberProof,
        bool $excludeTax
    ) : ?VatNumberProof;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VatNumberProof $vatNumberProof
     *
     * @return bool
     */
    public function delete(
        VatNumberProof $vatNumberProof
    ) : bool;
}
