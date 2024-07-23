<?php

namespace App\Repositories\Billing;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Billing;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Billing\Interfaces\BillingRepositoryInterface;
use Exception;

/**
 * Class BillingRepository
 *
 * @package App\Repositories\Billing
 */
class BillingRepository extends BaseRepository implements BillingRepositoryInterface
{
    /**
     * BillingRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.billing.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Billing|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Billing
    {
        try {
            return Billing::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Billing|null
     *
     * @throws DatabaseException
     */
    public function findByUser(
        User $user
    ) : ?Billing
    {
        try {
            return Billing::query()
                ->with([
                    'user',
                    'countryPlace',
                    'regionPlace',
                    'phoneCodeCountryPlace.defaultPhoneCode',
                    'vatNumberProofs.countryPlace',
                    'vatNumberProofs.excludeTaxHistory.admin'
                ])
                ->where('user_id', '=', $user->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : ?Billing
    {
        try {
            return Billing::query()->create([
                'user_id'                     => $user->id,
                'country_place_id'            => $countryPlace->place_id,
                'region_place_id'             => $regionPlace?->place_id,
                'phone_code_country_place_id' => $phoneCodeCountryPlace?->place_id,
                'first_name'                  => $firstName ? trim($firstName) : null,
                'last_name'                   => $lastName ? trim($lastName) : null,
                'city'                        => $city ? trim($city) : null,
                'postal_code'                 => $postalCode ? trim($postalCode) : null,
                'address'                     => $address ? trim($address) : null,
                'phone'                       => $phone ? trim($phone) : null,
                'business_info'               => !is_null($businessInfo) ? $businessInfo : false,
                'company_name'                => $companyName ? trim($companyName) : null,
                'vat_number'                  => $vatNumber ? trim($vatNumber) : null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : Billing
    {
        try {
            $billing->update([
                'country_place_id'            => $countryPlace ? $countryPlace->place_id : $billing->country_place_id,
                'region_place_id'             => $regionPlace ? $regionPlace->place_id : $billing->region_place_id,
                'phone_code_country_place_id' => $phoneCodeCountryPlace ? $phoneCodeCountryPlace->place_id : $billing->phone_code_country_place_id,
                'first_name'                  => $firstName ? trim($firstName) : $billing->first_name,
                'last_name'                   => $lastName ? trim($lastName) : $billing->last_name,
                'city'                        => $city ? trim($city) : $billing->city,
                'postal_code'                 => $postalCode ? trim($postalCode) : $billing->postal_code,
                'address'                     => $address ? trim($address) : $billing->address,
                'phone'                       => $phone ? trim($phone) : $billing->phone,
                'business_info'               => !is_null($businessInfo) ? $businessInfo : $billing->business_info,
                'company_name'                => $companyName ? trim($companyName) : $billing->company_name,
                'vat_number'                  => $vatNumber ? trim($vatNumber) : $billing->vat_number
            ]);

            return $billing;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     * @param CountryPlace $countryPlace
     *
     * @return Billing|null
     *
     * @throws DatabaseException
     */
    public function updateCountryPlace(
        Billing $billing,
        CountryPlace $countryPlace
    ) : ?Billing
    {
        try {
            $billing->update([
                'country_place_id' => $countryPlace->id
            ]);

            return $billing;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     * @param RegionPlace|null $regionPlace
     *
     * @return Billing|null
     *
     * @throws DatabaseException
     */
    public function updateRegionPlace(
        Billing $billing,
        ?RegionPlace $regionPlace
    ) : ?Billing
    {
        try {
            $billing->update([
                'region_place_id' => $regionPlace ? $regionPlace->place_id : $billing->region_place_id
            ]);

            return $billing;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     * @param CountryPlace $phoneCodeCountryPlace
     *
     * @return Billing|null
     *
     * @throws DatabaseException
     */
    public function updatePhoneCodeCountryPlace(
        Billing $billing,
        CountryPlace $phoneCodeCountryPlace
    ) : ?Billing
    {
        try {
            $billing->update([
                'phone_code_country_place_id' => $phoneCodeCountryPlace->place_id
            ]);

            return $billing;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     * @param CountryPlace $countryPlace
     * @param RegionPlace|null $regionPlace
     *
     * @return Billing
     *
     * @throws DatabaseException
     */
    public function updatePlaces(
        Billing $billing,
        CountryPlace $countryPlace,
        ?RegionPlace $regionPlace
    ) : Billing
    {
        try {
            $billing->update([
                'country_place_id' => $countryPlace->place_id,
                'region_place_id'  => $regionPlace?->place_id
            ]);

            return $billing;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForUser(
        User $user
    ) : bool
    {
        try {
            return $user->billing()
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Billing $billing
    ) : bool
    {
        try {
            return $billing->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billing.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
