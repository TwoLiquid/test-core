<?php

namespace App\Repositories\TaxRule\Interfaces;

use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TaxRuleCountryRepositoryInterface
 *
 * @package App\Repositories\TaxRule\Interfaces
 */
interface TaxRuleCountryRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TaxRuleCountry|null
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleCountry;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int $id
     * @param string|null $search
     *
     * @return TaxRuleCountry|null
     */
    public function findByIdWithSearch(
        int $id,
        ?string $search
    ) : ?TaxRuleCountry;

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
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
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
     * @param CountryPlace $countryPlace
     *
     * @return bool
     */
    public function countryPlaceExists(
        CountryPlace $countryPlace
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param CountryPlace $countryPlace
     * @param float $taxRate
     * @param string $fromDate
     *
     * @return TaxRuleCountry|null
     */
    public function store(
        CountryPlace $countryPlace,
        float $taxRate,
        string $fromDate
    ) : ?TaxRuleCountry;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param TaxRuleCountry $taxRuleCountry
     * @param float|null $taxRate
     * @param string|null $fromDate
     *
     * @return TaxRuleCountry
     */
    public function update(
        TaxRuleCountry $taxRuleCountry,
        ?float $taxRate,
        ?string $fromDate
    ) : TaxRuleCountry;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return bool
     */
    public function delete(
        TaxRuleCountry $taxRuleCountry
    ) : bool;
}
