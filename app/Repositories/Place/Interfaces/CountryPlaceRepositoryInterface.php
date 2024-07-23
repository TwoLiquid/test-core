<?php

namespace App\Repositories\Place\Interfaces;

use App\Models\MySql\Place\CountryPlace;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CountryPlaceRepositoryInterface
 *
 * @package App\Repositories\Place\Interfaces
 */
interface CountryPlaceRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return CountryPlace|null
     */
    public function findById(
        ?int $id
    ) : ?CountryPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string $placeId
     *
     * @return CountryPlace|null
     */
    public function findByPlaceId(
        string $placeId
    ) : ?CountryPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $name
     *
     * @return CountryPlace|null
     */
    public function findByName(
        string $name
    ) : ?CountryPlace;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $code
     *
     * @return CountryPlace|null
     */
    public function findByCode(
        string $code
    ) : ?CountryPlace;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAllWithoutExcluded() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string $search
     *
     * @return Collection
     */
    public function getAllBySearch(
        string $search
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param array $placeIds
     *
     * @return Collection
     */
    public function getByPlaceIds(
        array $placeIds
    ) : Collection;

    /**
     * This method provides creating row
     * with an eloquent model
     *
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
     */
    public function store(
        string $placeId,
        string $code,
        array $name,
        array $officialName,
        float $latitude,
        float $longitude,
        bool $hasRegions,
        bool $excluded
    ) : ?CountryPlace;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
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
    ) : CountryPlace;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CountryPlace $countryPlace
     * @param bool $excluded
     * @return CountryPlace
     */
    public function updateExcluded(
        CountryPlace $countryPlace,
        bool $excluded
    ) : CountryPlace;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param CountryPlace $countryPlace
     *
     * @return bool
     */
    public function delete(
        CountryPlace $countryPlace
    ) : bool;
}
