<?php

namespace App\Repositories\Place\Interfaces;

use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Timezone\Timezone;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CityPlaceRepositoryInterface
 *
 * @package App\Repositories\Place\Interfaces
 */
interface CityPlaceRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return CityPlace|null
     */
    public function findById(
        ?int $id
    ) : ?CityPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $placeId
     *
     * @return CityPlace|null
     */
    public function findByPlaceId(
        ?string $placeId
    ) : ?CityPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $placeId
     *
     * @return CityPlace|null
     */
    public function findFullByPlaceId(
        ?string $placeId
    ) : ?CityPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $name
     *
     * @return CityPlace|null
     */
    public function findByName(
        string $name
    ) : ?CityPlace;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @return Collection
     */
    public function getAllFromVybes() : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Timezone $timezone
     *
     * @return Collection
     */
    public function getByTimezone(
        Timezone $timezone
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Timezone $timezone
     * @param string $placeId
     * @param array $name
     * @param float $latitude
     * @param float $longitude
     *
     * @return CityPlace|null
     */
    public function store(
        Timezone $timezone,
        string $placeId,
        array $name,
        float $latitude,
        float $longitude
    ) : ?CityPlace;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CityPlace $cityPlace
     * @param Timezone|null $timezone
     * @param string|null $placeId
     * @param array|null $name
     * @param float|null $latitude
     * @param float|null $longitude
     *
     * @return CityPlace
     */
    public function update(
        CityPlace $cityPlace,
        ?Timezone $timezone,
        ?string $placeId,
        ?array $name,
        ?float $latitude,
        ?float $longitude
    ) : CityPlace;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CityPlace $cityPlace
     * @param Timezone $timezone
     *
     * @return CityPlace
     */
    public function updateTimezone(
        CityPlace $cityPlace,
        Timezone $timezone
    ) : CityPlace;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param CityPlace $cityPlace
     *
     * @return bool
     */
    public function delete(
        CityPlace $cityPlace
    ) : bool;
}
