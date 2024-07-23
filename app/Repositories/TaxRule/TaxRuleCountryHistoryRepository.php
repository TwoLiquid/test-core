<?php

namespace App\Repositories\TaxRule;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\TaxRule\TaxRuleCountryHistory;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use App\Repositories\BaseRepository;
use App\Repositories\TaxRule\Interfaces\TaxRuleCountryHistoryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class TaxRuleCountryHistoryRepository
 *
 * @package App\Repositories\TaxRule
 */
class TaxRuleCountryHistoryRepository extends BaseRepository implements TaxRuleCountryHistoryRepositoryInterface
{
    /**
     * TaxRuleCountryHistoryRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.taxRuleCountryHistory.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return TaxRuleCountryHistory|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleCountryHistory
    {
        try {
            return TaxRuleCountryHistory::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return TaxRuleCountryHistory|null
     *
     * @throws DatabaseException
     */
    public function findLastForTaxRuleCountry(
        TaxRuleCountry $taxRuleCountry
    ) : ?TaxRuleCountryHistory
    {
        try {
            return TaxRuleCountryHistory::query()
                ->where('tax_rule_country_id', '=', $taxRuleCountry->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
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
            return TaxRuleCountryHistory::all();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
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
            return TaxRuleCountryHistory::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountry $taxRuleCountry
     * @param float $fromTaxRate
     * @param string $fromDate
     * @param float|null $toTaxRate
     * @param string|null $toDate
     *
     * @return TaxRuleCountryHistory|null
     *
     * @throws DatabaseException
     */
    public function store(
        TaxRuleCountry $taxRuleCountry,
        float $fromTaxRate,
        string $fromDate,
        ?float $toTaxRate = null,
        ?string $toDate = null
    ) : ?TaxRuleCountryHistory
    {
        try {
            return TaxRuleCountryHistory::query()->create([
                'tax_rule_country_id' => $taxRuleCountry->id,
                'from_tax_rate'       => $fromTaxRate,
                'from_date'           => Carbon::parse($fromDate)->format('Y-m-d H:i:s'),
                'to_tax_rate'         => $toTaxRate,
                'to_date'             => $toDate ? Carbon::parse($toDate)->format('Y-m-d H:i:s') : null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountryHistory $taxRuleCountryHistory
     * @param float $toTaxRate
     * @param string $toDate
     *
     * @return TaxRuleCountryHistory
     *
     * @throws DatabaseException
     */
    public function update(
        TaxRuleCountryHistory $taxRuleCountryHistory,
        float $toTaxRate,
        string $toDate
    ) : TaxRuleCountryHistory
    {
        try {
            $taxRuleCountryHistory->update([
                'to_tax_rate' => $toTaxRate,
                'to_date'     => Carbon::parse($toDate)->format('Y-m-d H:i:s')
            ]);

            return $taxRuleCountryHistory;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountryHistory $taxRuleCountryHistory
     * @param Admin $admin
     *
     * @return TaxRuleCountryHistory
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        TaxRuleCountryHistory $taxRuleCountryHistory,
        Admin $admin
    ) : TaxRuleCountryHistory
    {
        try {
            $taxRuleCountryHistory->update([
                'admin_id' => $admin->id
            ]);

            return $taxRuleCountryHistory;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountryHistory $taxRuleCountryHistory
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TaxRuleCountryHistory $taxRuleCountryHistory
    ) : bool
    {
        try {
            return $taxRuleCountryHistory->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountryHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
