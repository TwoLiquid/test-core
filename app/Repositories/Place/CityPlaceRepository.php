<?php

namespace App\Repositories\Place;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Timezone\Timezone;
use App\Repositories\BaseRepository;
use App\Repositories\Place\Interfaces\CityPlaceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class CityPlaceRepository
 *
 * @package App\Repositories\Place
 */
class CityPlaceRepository extends BaseRepository implements CityPlaceRepositoryInterface
{
    /**
     * CityPlaceRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.cityPlace.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return CityPlace|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?CityPlace
    {
        try {
            return CityPlace::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $placeId
     *
     * @return CityPlace|null
     *
     * @throws DatabaseException
     */
    public function findByPlaceId(
        ?string $placeId
    ) : ?CityPlace
    {
        try {
            return CityPlace::query()
                ->where('place_id', '=', trim($placeId))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $placeId
     *
     * @return CityPlace|null
     *
     * @throws DatabaseException
     */
    public function findFullByPlaceId(
        ?string $placeId
    ) : ?CityPlace
    {
        try {
            return CityPlace::query()
                ->with([
                    'timezone.offsets'
                ])
                ->where('place_id', '=', trim($placeId))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return CityPlace|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?CityPlace
    {
        try {
            return CityPlace::query()
                ->where('name->en', '=', $name)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFromVybes() : Collection
    {
        try {
            return CityPlace::query()
                ->whereHas(
                    'appearanceCases'
                )
                ->distinct()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByTimezone(
        Timezone $timezone
    ) : Collection
    {
        try {
            return CityPlace::query()
                ->where('timezone_id', '=', $timezone->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     * @param string $placeId
     * @param array $name
     * @param float $latitude
     * @param float $longitude
     *
     * @return CityPlace|null
     *
     * @throws DatabaseException
     */
    public function store(
        Timezone $timezone,
        string $placeId,
        array $name,
        float $latitude,
        float $longitude
    ) : ?CityPlace
    {
        try {
            return CityPlace::query()->create([
                'timezone_id' => $timezone->id,
                'place_id'    => $placeId,
                'name'        => $name,
                'latitude'    => $latitude,
                'longitude'   => $longitude
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CityPlace $cityPlace
     * @param Timezone|null $timezone
     * @param string|null $placeId
     * @param array|null $name
     * @param float|null $latitude
     * @param float|null $longitude
     *
     * @return CityPlace
     *
     * @throws DatabaseException
     */
    public function update(
        CityPlace $cityPlace,
        ?Timezone $timezone,
        ?string $placeId,
        ?array $name,
        ?float $latitude,
        ?float $longitude
    ) : CityPlace
    {
        try {
            $cityPlace->update([
                'timezone_id' => $timezone ? $timezone->id : $cityPlace->timezone_id,
                'place_id'    => $placeId ?: $cityPlace->place_id,
                'name'        => $name ?: $cityPlace->name,
                'latitude'    => $latitude ?: $cityPlace->latitude,
                'longitude'   => $longitude ?: $cityPlace->longitude
            ]);

            return $cityPlace;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CityPlace $cityPlace
     * @param Timezone $timezone
     *
     * @return CityPlace
     *
     * @throws DatabaseException
     */
    public function updateTimezone(
        CityPlace $cityPlace,
        Timezone $timezone
    ) : CityPlace
    {
        try {
            $cityPlace->update([
                'timezone_id' => $timezone->id
            ]);

            return $cityPlace;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CityPlace $cityPlace
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        CityPlace $cityPlace
    ) : bool
    {
        try {
            return $cityPlace->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/place/cityPlace.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
