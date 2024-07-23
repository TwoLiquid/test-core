<?php

namespace App\Services\Payment;

use App\Lists\Payment\Type\PaymentTypeListItem;
use App\Services\Payment\Interfaces\PaymentServiceInterface;

/**
 * Class PaymentService
 *
 * @package App\Services\Payment
 */
abstract class PaymentService implements PaymentServiceInterface
{
    /**
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param int $id
     *
     * @return string
     */
    protected function getTransactionDescription(
        PaymentTypeListItem $paymentTypeListItem,
        int $id
    ) : string
    {
        return $paymentTypeListItem->name . ' #' . $id . ' payment transaction.';
    }

    /**
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param int $id
     * @param string $hash
     *
     * @return string
     */
    protected function getReturnUrl(
        PaymentTypeListItem $paymentTypeListItem,
        int $id,
        string $hash
    ) : string
    {
        return config('payment.returnUrl') . '?type=' . $paymentTypeListItem->code . '&id=' . $id . '&hash=' . $hash;
    }

    /**
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param int $id
     * @param string $hash
     *
     * @return string
     */
    protected function getCancelUrl(
        PaymentTypeListItem $paymentTypeListItem,
        int $id,
        string $hash
    ) : string
    {
        return config('payment.cancelUrl') . '?type=' . $paymentTypeListItem->code . '&id=' . $id . '&hash=' . $hash;
    }
}