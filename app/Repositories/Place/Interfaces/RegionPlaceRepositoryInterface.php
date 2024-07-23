<?php

namespace App\Repositories\Place\Interfaces;

use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface RegionPlaceRepositoryInterface
 *
 * @package App\Repositories\Place\Interfaces
 */
interface RegionPlaceRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return RegionPlace|null
     */
    public function findById(
        ?int $id
    ) : ?RegionPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $placeId
     *
     * @return RegionPlace|null
     */
    public function findByPlaceId(
        ?string $placeId
    ) : ?RegionPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $name
     *
     * @return RegionPlace|null
     */
    public function findByName(
        string $name
    ) : ?RegionPlace;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param CountryPlace $countryPlace
     *
     * @return Collection
     */
    public function getAllByCountryPlace(
        CountryPlace $countryPlace
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param CountryPlace $countryPlace
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllByCountryPlacePaginated(
        CountryPlace $countryPlace,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param CountryPlace $countryPlace
     * @param string $placeId
     * @param array $name
     * @param string|null $code
     *
     * @return RegionPlace|null
     */
    public function store(
        CountryPlace $countryPlace,
        string $placeId,
        array $name,
        ?string $code
    ) : ?RegionPlace;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param RegionPlace $regionPlace
     * @param CountryPlace|null $countryPlace
     * @param string|null $placeId
     * @param array|null $name
     * @param string|null $code
     *
     * @return RegionPlace
     */
    public function update(
        RegionPlace $regionPlace,
        ?CountryPlace $countryPlace,
        ?string $placeId,
        ?array $name,
        ?string $code
    ) : RegionPlace;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param RegionPlace $regionPlace
     *
     * @return bool
     */
    public function delete(
        RegionPlace $regionPlace
    ) : bool;
}
