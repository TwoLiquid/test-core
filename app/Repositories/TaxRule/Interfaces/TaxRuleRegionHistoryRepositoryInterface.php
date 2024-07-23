<?php

namespace App\Repositories\TaxRule\Interfaces;

use App\Models\MongoDb\TaxRule\TaxRuleRegionHistory;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\TaxRule\TaxRuleRegion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TaxRuleRegionHistoryRepositoryInterface
 *
 * @package App\Repositories\TaxRule\Interfaces
 */
interface TaxRuleRegionHistoryRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TaxRuleRegionHistory|null
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleRegionHistory;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param TaxRuleRegion $taxRuleRegion
     *
     * @return TaxRuleRegionHistory|null
     */
    public function findLastForTaxRuleRegion(
        TaxRuleRegion $taxRuleRegion
    ) : ?TaxRuleRegionHistory;

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
     * @param TaxRuleRegion $taxRuleRegion
     * @param float $fromTaxRate
     * @param string $fromDate
     * @param float|null $toTaxRate
     * @param string|null $toDate
     *
     * @return TaxRuleRegionHistory|null
     */
    public function store(
        TaxRuleRegion $taxRuleRegion,
        float $fromTaxRate,
        string $fromDate,
        ?float $toTaxRate,
        ?string $toDate
    ) : ?TaxRuleRegionHistory;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param TaxRuleRegionHistory $taxRuleRegionHistory
     * @param float $toTaxRate
     * @param string $toDate
     *
     * @return TaxRuleRegionHistory
     */
    public function update(
        TaxRuleRegionHistory $taxRuleRegionHistory,
        float $toTaxRate,
        string $toDate
    ) : TaxRuleRegionHistory;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param TaxRuleRegionHistory $taxRuleRegionHistory
     * @param Admin $admin
     *
     * @return TaxRuleRegionHistory
     */
    public function updateAdmin(
        TaxRuleRegionHistory $taxRuleRegionHistory,
        Admin $admin
    ) : TaxRuleRegionHistory;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TaxRuleRegionHistory $taxRuleRegionHistory
     *
     * @return bool
     */
    public function delete(
        TaxRuleRegionHistory $taxRuleRegionHistory
    ) : bool;
}
