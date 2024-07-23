<?php

namespace App\Repositories\Place;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\CountryPlace;
use App\Repositories\BaseRepository;
use App\Repositories\Place\Interfaces\CountryPlaceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class CountryPlaceRepository
 *
 * @package App\Repositories\Place
 */
class CountryPlaceRepository extends BaseRepository implements CountryPlaceRepositoryInterface
{
    /**
     * CountryPlaceRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.countryPlace.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return CountryPlace|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?CountryPlace
    {
        try {
            return CountryPlace::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $placeId
     *
     * @return CountryPlace|null
     *
     * @throws DatabaseException
     */
    public function findByPlaceId(
        string $placeId
    ) : ?CountryPlace
    {
        try {
            return CountryPlace::query()
                ->where('place_id', '=', trim($placeId))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return CountryPlace|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?CountryPlace
    {
        try {
            return CountryPlace::query()
                ->where('name->en', '%LIKE%', trim($name))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $code
     *
     * @return CountryPlace|null
     *
     * @throws DatabaseException
     */
    public function findByCode(
        string $code
    ) : ?CountryPlace
    {
        try {
            return CountryPlace::query()
                ->where('code', '=', trim($code))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
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
            return CountryPlace::query()
                ->with([
                    'defaultPhoneCode'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllWithoutExcluded() : Collection
    {
        try {
            return CountryPlace::query()
                ->with([
                    'defaultPhoneCode'
                ])
                ->where('excluded', '=', false)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
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
            return CountryPlace::query()
                ->with([
                    'defaultPhoneCode'
                ])
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
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
            return CountryPlace::query()
                ->with([
                    'defaultPhoneCode'
                ])
                ->where('name->en', '%LIKE%', $search)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
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
            return CountryPlace::query()
                ->with([
                    'defaultPhoneCode'
                ])
                ->where('name->en', '%LIKE%', $search)
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $placeIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByPlaceIds(
        array $placeIds
    ) : Collection
    {
        try {
            return CountryPlace::query()
                ->whereIn('place_id', $placeIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $placeId
     * @param string $code
     * @param array $name
     * @param array $officialName
     * @param float $latitude
     * @param float $longitude
     * @param bool $hasRegions
     * @param bool $excluded
     *
     * @return CountryPlace|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $placeId,
        string $code,
        array $name,
        array $officialName,
        float $latitude,
        float $longitude,
        bool $hasRegions = false,
        bool $excluded = false
    ) : ?CountryPlace
    {
        try {
            return CountryPlace::query()->create([
                'place_id'      => $placeId,
                'code'          => $code,
                'name'          => $name,
                'official_name' => $officialName,
                'longitude'     => $longitude,
                'latitude'      => $latitude,
                'has_regions'   => $hasRegions,
                'excluded'      => $excluded
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     * @param string|null $placeId
     * @param string|null $code
     * @param array|null $name
     * @param array|null $officialName
     * @param float|null $latitude
     * @param float|null $longitude
     * @param bool|null $hasRegions
     * @param bool|null $excluded
     *
     * @return CountryPlace
     *
     * @throws DatabaseException
     */
    public function update(
        CountryPlace $countryPlace,
        ?string $placeId,
        ?string $code,
        ?array $name,
        ?array $officialName,
        ?float $latitude,
        ?float $longitude,
        ?bool $hasRegions,
        ?bool $excluded
    ) : CountryPlace
    {
        try {
            $countryPlace->update([
                'place_id'      => $placeId ?: $countryPlace->place_id,
                'code'          => $code ?: $countryPlace->code,
                'name'          => $name ?: $countryPlace->name,
                'official_name' => $officialName ?: $countryPlace->official_name,
                'latitude'      => $latitude ?: $countryPlace->latitude,
                'longitude'     => $longitude ?: $countryPlace->longitude,
                'has_regions'   => $hasRegions ?: $countryPlace->has_regions,
                'excluded'      => $excluded ?: $countryPlace->excluded
            ]);

            return $countryPlace;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     * @param bool $excluded
     *
     * @return CountryPlace
     *
     * @throws DatabaseException
     */
    public function updateExcluded(
        CountryPlace $countryPlace,
        bool $excluded
    ) : CountryPlace
    {
        try {
            $countryPlace->update([
                'excluded' => $excluded
            ]);

            return $countryPlace;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
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
    public function delete(
        CountryPlace $countryPlace
    ) : bool
    {
        try {
            return $countryPlace->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/countryPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
