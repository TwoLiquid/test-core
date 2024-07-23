<?php

namespace App\Repositories\Place;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Repositories\BaseRepository;
use App\Repositories\Place\Interfaces\RegionPlaceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class RegionPlaceRepository
 * 
 * @package App\Repositories\Place
 */
class RegionPlaceRepository extends BaseRepository implements RegionPlaceRepositoryInterface
{
    /**
     * RegionPlaceRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.regionPlace.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return RegionPlace|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?RegionPlace
    {
        try {
            return RegionPlace::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $placeId
     *
     * @return RegionPlace|null
     *
     * @throws DatabaseException
     */
    public function findByPlaceId(
        ?string $placeId
    ) : ?RegionPlace
    {
        try {
            return RegionPlace::query()
                ->where('place_id', '=', $placeId)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return RegionPlace|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?RegionPlace
    {
        try {
            return RegionPlace::query()
                ->where('name->en', '=', $name)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllByCountryPlace(
        CountryPlace $countryPlace
    ) : Collection
    {
        try {
            return RegionPlace::query()
                ->where('country_place_id', '=', $countryPlace->place_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllByCountryPlacePaginated(
        CountryPlace $countryPlace,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return RegionPlace::query()
                ->where('country_place_id', '=', $countryPlace->place_id)
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     * @param string $placeId
     * @param array $name
     * @param string|null $code
     *
     * @return RegionPlace|null
     *
     * @throws DatabaseException
     */
    public function store(
        CountryPlace $countryPlace,
        string $placeId,
        array $name,
        ?string $code
    ) : ?RegionPlace
    {
        try {
            return RegionPlace::query()->create([
                'country_place_id' => $countryPlace->place_id,
                'place_id'         => $placeId,
                'name'             => $name,
                'code'             => $code
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param RegionPlace $regionPlace
     * @param CountryPlace|null $countryPlace
     * @param string|null $placeId
     * @param array|null $name
     * @param string|null $code
     *
     * @return RegionPlace
     *
     * @throws DatabaseException
     */
    public function update(
        RegionPlace $regionPlace,
        ?CountryPlace $countryPlace,
        ?string $placeId,
        ?array $name,
        ?string $code
    ) : RegionPlace
    {
        try {
            $regionPlace->update([
                'country_place_id' => $countryPlace ? $countryPlace->place_id : $regionPlace->country_place_id,
                'place_id'         => $placeId ?: $regionPlace->place_id,
                'name'             => $name ?: $regionPlace->name,
                'code'             => $code ?: $regionPlace->code
            ]);

            return $regionPlace;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
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
    public function delete(
        RegionPlace $regionPlace
    ) : bool
    {
        try {
            return $regionPlace->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/regionPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}