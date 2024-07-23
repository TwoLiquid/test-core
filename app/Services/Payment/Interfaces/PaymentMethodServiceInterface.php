<?php

namespace App\Services\Payment\Interfaces;

use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PaymentMethodServiceInterface
 *
 * @package App\Services\Payment\Interfaces
 */
interface PaymentMethodServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param PaymentMethod $paymentMethod
     *
     * @return bool
     */
    public function isBalance(
        PaymentMethod $paymentMethod
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param PaymentMethod $paymentMethod
     * @param array $fields
     *
     * @return bool
     */
    public function validateFields(
        PaymentMethod $paymentMethod,
        array $fields
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param PaymentMethod $paymentMethod
     * @param array $fields
     *
     * @return Collection
     */
    public function createFields(
        PaymentMethod $paymentMethod,
        array $fields
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param PaymentMethod $paymentMethod
     * @param array $fields
     *
     * @return Collection
     */
    public function updateFields(
        PaymentMethod $paymentMethod,
        array $fields
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param array $fields
     */
    public function updateFieldsUserValues(
        User $user,
        array $fields
    ) : void;

    /**
     * This method provides getting data
     *
     * @param Collection $payoutMethodRequests
     *
     * @return Collection
     */
    public function getByPayoutMethodRequests(
        Collection $payoutMethodRequests
    ) : Collection;

    /**
     * This method provides creating data
     *
     * @param PaymentMethod $paymentMethod
     * @param Collection $countryPlaces
     * @param bool $excluded
     *
     * @return void
     */
    public function updateCountryPlaces(
        PaymentMethod $paymentMethod,
        Collection $countryPlaces,
        bool $excluded
    ) : void;

    /**
     * This method provides getting data
     *
     * @param Collection $paymentMethods
     * @param User $user
     * @param bool $admin
     *
     * @return Collection
     */
    public function getForUser(
        Collection $paymentMethods,
        User $user,
        bool $admin
    ) : Collection;
}
