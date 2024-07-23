<?php

namespace App\Services\Payment\Interfaces;

use App\Lists\Currency\CurrencyListItem;
use App\Lists\Payment\Type\PaymentTypeListItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PaymentServiceInterface
 *
 * @package App\Services\Payment\Interfaces
 */
interface PaymentServiceInterface
{
    /**
     * This method provides getting data
     *
     * @return bool
     */
    public function canGetAuthToken() : bool;

    /**
     * This method provides getting data
     *
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param CurrencyListItem $currencyListItem
     * @param int $id
     * @param float $amount
     * @param string $hash
     *
     * @return string
     */
    public function getPaymentUrl(
        PaymentTypeListItem $paymentTypeListItem,
        CurrencyListItem $currencyListItem,
        int $id,
        float $amount,
        string $hash
    ) : string;

    /**
     * This method provides creating data
     *
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param int $id
     * @param string $hash
     *
     * @return Collection
     */
    public function executePayment(
        PaymentTypeListItem $paymentTypeListItem,
        int $id,
        string $hash
    ) : Collection;
}
