<?php

namespace App\Repositories\TaxRule;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use App\Repositories\BaseRepository;
use App\Repositories\TaxRule\Interfaces\TaxRuleCountryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class TaxRuleCountryRepository
 *
 * @package App\Repositories\TaxRule
 */
class TaxRuleCountryRepository extends BaseRepository implements TaxRuleCountryRepositoryInterface
{
    /**
     * TaxRuleCountryRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.taxRuleCountry.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return TaxRuleCountry|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?TaxRuleCountry
    {
        try {
            return TaxRuleCountry::query()
                ->with([
                    'countryPlace',
                    'taxRuleRegions' => function ($query) {
                        $query->select([
                            'id',
                            'tax_rule_country_id',
                            'region_place_id',
                            'tax_rate',
                            'from_date'
                        ])
                        ->with([
                            'regionPlace' => function ($query) {
                                $query->select([
                                    'id',
                                    'place_id',
                                    'name',
                                    'code'
                                ]);
                            }
                        ]);
                    }
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int $id
     * @param string|null $search
     *
     * @return TaxRuleCountry|null
     *
     * @throws DatabaseException
     */
    public function findByIdWithSearch(
        int $id,
        ?string $search
    ) : ?TaxRuleCountry
    {
        try {
            return TaxRuleCountry::query()
                ->with([
                    'countryPlace',
                    'taxRuleRegions' => function ($query) use ($search) {
                        $query->select([
                            'id',
                            'tax_rule_country_id',
                            'region_place_id',
                            'tax_rate',
                            'from_date'
                        ])->with([
                            'regionPlace' => function ($query) {
                                $query->select([
                                    'id',
                                    'place_id',
                                    'name',
                                    'code'
                                ]);
                            }
                        ])->when($search, function ($query) use ($search) {
                            $query->whereHas('regionPlace', function ($query) use ($search) {
                                $query->where('name->en', 'LIKE', '%' . $search . '%');
                            });
                        });
                    }
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
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
            return TaxRuleCountry::query()
                ->with([
                    'countryPlace'
                ])
                ->withCount([
                    'taxRuleRegions'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
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
            return TaxRuleCountry::query()
                ->with([
                    'countryPlace'
                ])
                ->whereHas('countryPlace', function ($query) use ($search) {
                    $query->where('name->en', 'LIKE', '%'. $search . '%');
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
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
            return TaxRuleCountry::query()
                ->with([
                    'countryPlace'
                ])
                ->withCount([
                    'taxRuleRegions'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
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
            return TaxRuleCountry::query()
                ->with([
                    'countryPlace'
                ])
                ->whereHas('countryPlace', function ($query) use ($search) {
                    $query->where('name->en', 'LIKE', '%'. $search . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function countryPlaceExists(
        CountryPlace $countryPlace
    ) : bool
    {
        try {
            return TaxRuleCountry::query()
                ->where('country_place_id', '=', $countryPlace->place_id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     * @param float $taxRate
     * @param string $fromDate
     *
     * @return TaxRuleCountry|null
     *
     * @throws DatabaseException
     */
    public function store(
        CountryPlace $countryPlace,
        float $taxRate,
        string $fromDate
    ) : ?TaxRuleCountry
    {
        try {
            return TaxRuleCountry::query()->create([
                'country_place_id' => $countryPlace->place_id,
                'tax_rate'         => $taxRate,
                'from_date'        => Carbon::parse($fromDate)->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountry $taxRuleCountry
     * @param float|null $taxRate
     * @param string|null $fromDate
     *
     * @return TaxRuleCountry
     *
     * @throws DatabaseException
     */
    public function update(
        TaxRuleCountry $taxRuleCountry,
        ?float $taxRate,
        ?string $fromDate
    ) : TaxRuleCountry
    {
        try {
            $taxRuleCountry->update([
                'tax_rate'  => $taxRate ?: $taxRuleCountry->tax_rate,
                'from_date' => $fromDate ? Carbon::parse($fromDate)->format('Y-m-d H:i:s') : $taxRuleCountry->from_date
            ]);

            return $taxRuleCountry;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TaxRuleCountry $taxRuleCountry
    ) : bool
    {
        try {
            return $taxRuleCountry->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/taxRule/taxRuleCountry.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
