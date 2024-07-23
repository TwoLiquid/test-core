<?php

namespace App\Repositories\TaxRule;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\TaxRule\TaxRuleRegionHistory;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\TaxRule\TaxRuleRegion;
use App\Repositories\BaseRepository;
use App\Repositories\TaxRule\Interfaces\TaxRuleRegionHistoryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class TaxRuleRegionHistoryRepository
 *
 * @package App\Repositories\TaxRule
 */
class TaxRuleRegionHistoryRepository extends BaseRepository implements TaxRuleRegionHistoryRepositoryInterface
{
    /**
     * TaxRuleRegionHistoryRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.taxRuleRegionHistory.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return TaxRuleRegionHistory|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleRegionHistory
    {
        try {
            return TaxRuleRegionHistory::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleRegion $taxRuleRegion
     *
     * @return TaxRuleRegionHistory|null
     *
     * @throws DatabaseException
     */
    public function findLastForTaxRuleRegion(
        TaxRuleRegion $taxRuleRegion
    ) : ?TaxRuleRegionHistory
    {
        try {
            return TaxRuleRegionHistory::query()
                ->where('tax_rule_region_id', '=', $taxRuleRegion->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return TaxRuleRegionHistory::all();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return TaxRuleRegionHistory::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleRegion $taxRuleRegion
     * @param float $fromTaxRate
     * @param string $fromDate
     * @param float|null $toTaxRate
     * @param string|null $toDate
     *
     * @return TaxRuleRegionHistory|null
     *
     * @throws DatabaseException
     */
    public function store(
        TaxRuleRegion $taxRuleRegion,
        float $fromTaxRate,
        string $fromDate,
        ?float $toTaxRate = null,
        ?string $toDate = null
    ) : ?TaxRuleRegionHistory
    {
        try {
            return TaxRuleRegionHistory::query()->create([
                'tax_rule_region_id' => $taxRuleRegion->id,
                'from_tax_rate'      => $fromTaxRate,
                'from_date'          => Carbon::parse($fromDate)->format('Y-m-d H:i:s'),
                'to_tax_rate'        => $toTaxRate,
                'to_date'            => $toDate ? Carbon::parse($toDate)->format('Y-m-d H:i:s') : null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleRegionHistory $taxRuleRegionHistory
     * @param float $toTaxRate
     * @param string $toDate
     *
     * @return TaxRuleRegionHistory
     *
     * @throws DatabaseException
     */
    public function update(
        TaxRuleRegionHistory $taxRuleRegionHistory,
        float $toTaxRate,
        string $toDate
    ) : TaxRuleRegionHistory
    {
        try {
            $taxRuleRegionHistory->update([
                'to_tax_rate' => $toTaxRate,
                'to_date'     => Carbon::parse($toDate)->format('Y-m-d H:i:s')
            ]);

            return $taxRuleRegionHistory;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleRegionHistory $taxRuleRegionHistory
     * @param Admin $admin
     *
     * @return TaxRuleRegionHistory
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        TaxRuleRegionHistory $taxRuleRegionHistory,
        Admin $admin
    ) : TaxRuleRegionHistory
    {
        try {
            $taxRuleRegionHistory->update([
                'admin_id' => $admin->id
            ]);

            return $taxRuleRegionHistory;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleRegionHistory $taxRuleRegionHistory
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TaxRuleRegionHistory $taxRuleRegionHistory
    ) : bool
    {
        try {
            return $taxRuleRegionHistory->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegionHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
