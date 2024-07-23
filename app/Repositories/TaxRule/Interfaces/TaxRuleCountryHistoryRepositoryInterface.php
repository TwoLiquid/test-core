<?php

namespace App\Repositories\TaxRule\Interfaces;

use App\Models\MongoDb\TaxRule\TaxRuleCountryHistory;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TaxRuleCountryHistoryRepositoryInterface
 *
 * @package App\Repositories\TaxRule\Interfaces
 */
interface TaxRuleCountryHistoryRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TaxRuleCountryHistory|null
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleCountryHistory;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return TaxRuleCountryHistory|null
     */
    public function findLastForTaxRuleCountry(
        TaxRuleCountry $taxRuleCountry
    ) : ?TaxRuleCountryHistory;

    /**
     * This method provides getting rows
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
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param TaxRuleCountry $taxRuleCountry
     * @param float $fromTaxRate
     * @param string $fromDate
     * @param float|null $toTaxRate
     * @param string|null $toDate
     *
     * @return TaxRuleCountryHistory|null
     */
    public function store(
        TaxRuleCountry $taxRuleCountry,
        float $fromTaxRate,
        string $fromDate,
        ?float $toTaxRate,
        ?string $toDate
    ) : ?TaxRuleCountryHistory;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param TaxRuleCountryHistory $taxRuleCountryHistory
     * @param float $toTaxRate
     * @param string $toDate
     *
     * @return TaxRuleCountryHistory
     */
    public function update(
        TaxRuleCountryHistory $taxRuleCountryHistory,
        float $toTaxRate,
        string $toDate
    ) : TaxRuleCountryHistory;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param TaxRuleCountryHistory $taxRuleCountryHistory
     * @param Admin $admin
     *
     * @return TaxRuleCountryHistory
     */
    public function updateAdmin(
        TaxRuleCountryHistory $taxRuleCountryHistory,
        Admin $admin
    ) : TaxRuleCountryHistory;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TaxRuleCountryHistory $taxRuleCountryHistory
     *
     * @return bool
     */
    public function delete(
        TaxRuleCountryHistory $taxRuleCountryHistory
    ) : bool;
}
