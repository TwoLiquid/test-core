<?php

namespace App\Repositories\PhoneCode\Interfaces;

use App\Models\MySql\PhoneCode;
use App\Models\MySql\Place\CountryPlace;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PhoneCodeRepositoryInterface
 *
 * @package App\Repositories\PhoneCode\Interfaces
 */
interface PhoneCodeRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PhoneCode|null
     */
    public function findById(
        ?int $id
    ) : ?PhoneCode;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

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
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param CountryPlace $countryPlace
     * @param string $code
     * @param bool $isDefault
     *
     * @return PhoneCode|null
     */
    public function store(
        CountryPlace $countryPlace,
        string $code,
        bool $isDefault
    ) : ?PhoneCode;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param PhoneCode $phoneCode
     * @param CountryPlace|null $countryPlace
     * @param string|null $code
     * @param bool|null $isDefault
     *
     * @return PhoneCode
     */
    public function update(
        PhoneCode $phoneCode,
        ?CountryPlace $countryPlace,
        ?string $code,
        ?bool $isDefault
    ) : PhoneCode;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param PhoneCode $phoneCode
     *
     * @return bool
     */
    public function delete(
        PhoneCode $phoneCode
    ) : bool;
}
