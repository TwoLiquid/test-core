<?php

namespace App\Repositories\TaxRule;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use App\Models\MySql\TaxRule\TaxRuleRegion;
use App\Repositories\BaseRepository;
use App\Repositories\TaxRule\Interfaces\TaxRuleRegionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class TaxRuleRegionRepository
 *
 * @package App\Repositories\TaxRule
 */
class TaxRuleRegionRepository extends BaseRepository implements TaxRuleRegionRepositoryInterface
{
    /**
     * TaxRuleRegionRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.taxRuleRegion.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return TaxRuleRegion|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleRegion
    {
        try {
            return TaxRuleRegion::query()
                ->with([
                    'regionPlace'
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
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
            return TaxRuleRegion::query()
                ->with([
                    'regionPlace'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllBySearch(
        string $search
    ) : Collection
    {
        try {
            return TaxRuleRegion::query()
                ->with([
                    'regionPlace'
                ])
                ->whereHas('regionPlace', function ($query) use ($search) {
                    $query->where('name->en', 'LIKE', '%'. $search . '%');
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return TaxRuleRegion::query()
                ->with([
                    'regionPlace'
                ])
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     * 
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return TaxRuleRegion::query()
                ->with([
                    'regionPlace'
                ])
                ->whereHas('regionPlace', function ($query) use ($search) {
                    $query->where('name->en', 'LIKE', '%'. $search . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param RegionPlace $regionPlace
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function regionPlaceExists(
        RegionPlace $regionPlace
    ) : bool
    {
        try {
            return TaxRuleRegion::query()
                ->where('region_place_id', '=', $regionPlace->place_id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountry $taxRuleCountry
     * @param RegionPlace $regionPlace
     * @param float $taxRate
     * @param string $fromDate
     *
     * @return TaxRuleRegion|null
     *
     * @throws DatabaseException
     */
    public function store(
        TaxRuleCountry $taxRuleCountry,
        RegionPlace $regionPlace,
        float $taxRate,
        string $fromDate
    ) : ?TaxRuleRegion
    {
        try {
            return TaxRuleRegion::query()->create([
                'tax_rule_country_id' => $taxRuleCountry->id,
                'region_place_id'     => $regionPlace->place_id,
                'tax_rate'            => $taxRate,
                'from_date'           => Carbon::parse($fromDate)->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleRegion $taxRuleRegion
     * @param float|null $taxRate
     * @param string|null $fromDate
     *
     * @return TaxRuleRegion
     *
     * @throws DatabaseException
     */
    public function update(
        TaxRuleRegion $taxRuleRegion,
        ?float $taxRate,
        ?string $fromDate
    ) : TaxRuleRegion
    {
        try {
            $taxRuleRegion->update([
                'tax_rate'  => $taxRate ?: $taxRuleRegion->tax_rate,
                'from_date' => $fromDate ? Carbon::parse($fromDate)->format('Y-m-d H:i:s') : $taxRuleRegion->from_date
            ]);

            return $taxRuleRegion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleRegion $taxRuleRegion
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TaxRuleRegion $taxRuleRegion
    ) : bool
    {
        try {
            return $taxRuleRegion->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleRegion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}