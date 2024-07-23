<?php

namespace App\Repositories\TaxRule\Interfaces;

use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use App\Models\MySql\TaxRule\TaxRuleRegion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TaxRuleRegionRepositoryInterface
 *
 * @package App\Repositories\TaxRule\Interfaces
 */
interface TaxRuleRegionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TaxRuleRegion|null
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleRegion;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string $search
     *
     * @return Collection
     */
    public function getAllBySearch(
        string $search
    ) : Collection;

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
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param RegionPlace $regionPlace
     *
     * @return bool
     */
    public function regionPlaceExists(
        RegionPlace $regionPlace
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param TaxRuleCountry $taxRuleCountry
     * @param RegionPlace $regionPlace
     * @param float $taxRate
     * @param string $fromDate
     *
     * @return TaxRuleRegion|null
     */
    public function store(
        TaxRuleCountry $taxRuleCountry,
        RegionPlace $regionPlace,
        float $taxRate,
        string $fromDate
    ) : ?TaxRuleRegion;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param TaxRuleRegion $taxRuleRegion
     * @param float|null $taxRate
     * @param string|null $fromDate
     *
     * @return TaxRuleRegion
     */
    public function update(
        TaxRuleRegion $taxRuleRegion,
        ?float $taxRate,
        ?string $fromDate
    ) : TaxRuleRegion;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TaxRuleRegion $taxRuleRegion
     *
     * @return bool
     */
    public function delete(
        TaxRuleRegion $taxRuleRegion
    ) : bool;
}
