<?php

namespace App\Repositories\Payment\Interfaces;

use App\Lists\Payment\Method\Payment\Status\PaymentStatusListItem;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface PaymentMethodRepositoryInterface
 *
 * @package App\Repositories\Payment\Interfaces
 */
interface PaymentMethodRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PaymentMethod|null
     */
    public function findById(
        ?int $id
    ) : ?PaymentMethod;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PaymentMethod|null
     */
    public function findFullById(
        ?int $id
    ) : ?PaymentMethod;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $name
     *
     * @return PaymentMethod|null
     */
    public function findByName(
        string $name
    ) : ?PaymentMethod;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $code
     *
     * @return PaymentMethod|null
     */
    public function findByCode(
        string $code
    ) : ?PaymentMethod;

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
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAllForAdmin() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllForAdminPaginated(
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param bool|null $standard
     *
     * @return Collection
     */
    public function getAllPaymentIntegrated(
        ?bool $standard
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @return Collection
     */
    public function getAllWithdrawalIntegrated() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getForUser(
        User $user
    ) : Collection;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return bool
     */
    public function existsForUser(
        PaymentMethod $paymentMethod,
        User $user
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param PaymentStatusListItem $paymentStatusListItem
     * @param PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem
     * @param string $name
     * @param string $code
     * @param float $paymentFee
     * @param bool $orderForm
     * @param array $displayName
     * @param array $durationTitle
     * @param array $durationAmount
     * @param array $feeTitle
     * @param array $feeAmount
     * @param bool $integrated
     * @param bool $standard
     *
     * @return PaymentMethod|null
     */
    public function store(
        PaymentStatusListItem $paymentStatusListItem,
        PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem,
        string $name,
        string $code,
        float $paymentFee,
        bool $orderForm,
        array $displayName,
        array $durationTitle,
        array $durationAmount,
        array $feeTitle,
        array $feeAmount,
        bool $integrated,
        bool $standard
    ) : ?PaymentMethod;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param PaymentMethod $paymentMethod
     * @param PaymentStatusListItem|null $paymentStatusListItem
     * @param PaymentMethodWithdrawalStatusListItem|null $paymentMethodWithdrawalStatusListItem
     * @param string|null $name
     * @param string|null $code
     * @param float|null $paymentFee
     * @param bool|null $orderForm
     * @param array|null $displayName
     * @param array|null $durationTitle
     * @param array|null $durationAmount
     * @param array|null $feeTitle
     * @param array|null $feeAmount
     * @param bool|null $integrated
     * @param bool|null $standard
     *
     * @return PaymentMethod
     */
    public function update(
        PaymentMethod $paymentMethod,
        ?PaymentStatusListItem $paymentStatusListItem,
        ?PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem,
        ?string $name,
        ?string $code,
        ?float $paymentFee,
        ?bool $orderForm,
        ?array $displayName,
        ?array $durationTitle,
        ?array $durationAmount,
        ?array $feeTitle,
        ?array $feeAmount,
        ?bool $integrated,
        ?bool $standard
    ) : PaymentMethod;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param PaymentMethod $paymentMethod
     * @param bool $integrated
     *
     * @return PaymentMethod
     */
    public function updateIntegrated(
        PaymentMethod $paymentMethod,
        bool $integrated
    ) : PaymentMethod;

    /**
     * This method provides attaching existing row
     * with a current model with belongs to many relations
     *
     * @param PaymentMethod $paymentMethod
     * @param CountryPlace $countryPlace
     * @param bool $excluded
     * @param bool|null $detaching
     *
     * @return void
     */
    public function attachCountryPlace(
        PaymentMethod $paymentMethod,
        CountryPlace $countryPlace,
        bool $excluded,
        ?bool $detaching
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param PaymentMethod $paymentMethod
     * @param array $countryPlacesIds
     * @param bool|null $detaching
     *
     * @return void
     */
    public function attachCountryPlaces(
        PaymentMethod $paymentMethod,
        array $countryPlacesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param PaymentMethod $paymentMethod
     * @param CountryPlace $countryPlace
     */
    public function detachCountryPlace(
        PaymentMethod $paymentMethod,
        CountryPlace $countryPlace
    ) : void;

    /**
     * This method provides detaching existing rows
     * with a current model with belongs to many relations
     *
     * @param PaymentMethod $paymentMethod
     * @param array $countryPlacesIds
     */
    public function detachCountryPlaces(
        PaymentMethod $paymentMethod,
        array $countryPlacesIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param PaymentMethod $paymentMethod
     *
     * @return bool
     */
    public function delete(
        PaymentMethod $paymentMethod
    ) : bool;
}
