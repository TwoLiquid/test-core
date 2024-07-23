<?php

namespace App\Repositories\Billing\Interfaces;

use App\Models\MySql\Billing;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\User\User;

/**
 * Interface BillingRepositoryInterface
 *
 * @package App\Repositories\Billing\Interfaces
 */
interface BillingRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Billing|null
     */
    public function findById(
        ?int $id
    ) : ?Billing;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Billing|null
     */
    public function findByUser(
        User $user
    ) : ?Billing;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param CountryPlace $countryPlace
     * @param RegionPlace|null $regionPlace
     * @param CountryPlace|null $phoneCodeCountryPlace
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $city
     * @param string|null $postalCode
     * @param string|null $address
     * @param string|null $phone
     * @param bool|null $businessInfo
     * @param string|null $companyName
     * @param string|null $vatNumber
     *
     * @return Billing|null
     */
    public function store(
        User $user,
        CountryPlace $countryPlace,
        ?RegionPlace $regionPlace,
        ?CountryPlace $phoneCodeCountryPlace,
        ?string $firstName,
        ?string $lastName,
        ?string $city,
        ?string $postalCode,
        ?string $address,
        ?string $phone,
        ?bool $businessInfo,
        ?string $companyName,
        ?string $vatNumber
    ) : ?Billing;

    /**
     * This method provides updating new row
     * with an eloquent model
     *
     * @param Billing $billing
     * @param CountryPlace|null $countryPlace
     * @param RegionPlace|null $regionPlace
     * @param CountryPlace|null $phoneCodeCountryPlace
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $city
     * @param string|null $postalCode
     * @param string|null $address
     * @param string|null $phone
     * @param bool|null $businessInfo
     * @param string|null $companyName
     * @param string|null $vatNumber
     *
     * @return Billing
     */
    public function update(
        Billing $billing,
        ?CountryPlace $countryPlace,
        ?RegionPlace $regionPlace,
        ?CountryPlace $phoneCodeCountryPlace,
        ?string $firstName,
        ?string $lastName,
        ?string $city,
        ?string $postalCode,
        ?string $address,
        ?string $phone,
        ?bool $businessInfo,
        ?string $companyName,
        ?string $vatNumber
    ) : Billing;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Billing $billing
     * @param CountryPlace $countryPlace
     *
     * @return Billing|null
     */
    public function updateCountryPlace(
        Billing $billing,
        CountryPlace $countryPlace
    ) : ?Billing;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Billing $billing
     * @param RegionPlace|null $regionPlace
     *
     * @return Billing|null
     */
    public function updateRegionPlace(
        Billing $billing,
        ?RegionPlace $regionPlace
    ) : ?Billing;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Billing $billing
     * @param CountryPlace $phoneCodeCountryPlace
     *
     * @return Billing|null
     */
    public function updatePhoneCodeCountryPlace(
        Billing $billing,
        CountryPlace $phoneCodeCountryPlace
    ) : ?Billing;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Billing $billing
     * @param CountryPlace $countryPlace
     * @param RegionPlace|null $regionPlace
     *
     * @return Billing
     */
    public function updatePlaces(
        Billing $billing,
        CountryPlace $countryPlace,
        ?RegionPlace $regionPlace
    ) : Billing;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteForUser(
        User $user
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Billing $billing
     *
     * @return bool
     */
    public function delete(
        Billing $billing
    ) : bool;
}
