<?php

namespace App\Services\Tip\Interfaces;

use App\Models\MySql\Billing;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\User\User;

/**
 * Interface TipServiceInterface
 *
 * @package App\Services\Tip\Interfaces
 */
interface TipServiceInterface
{
    /**
     * This method provides creating data
     * by related entity repository with a certain query
     *
     * @param User $user
     * @param PaymentMethod $paymentMethod
     * @param OrderItem $orderItem
     * @param float $amount
     * @param string|null $comment
     *
     * @return Tip
     */
    public function createTip(
        User $user,
        PaymentMethod $paymentMethod,
        OrderItem $orderItem,
        float $amount,
        ?string $comment
    ) : Tip;

    /**
     * This method provides checking data
     *
     * @param Tip $tip
     * @param string $hash
     *
     * @return bool
     */
    public function checkHash(
        Tip $tip,
        string $hash
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Billing $billing
     * @param float $amount
     *
     * @return float
     */
    public function getAmountTax(
        Billing $billing,
        float $amount
    ) : float;

    /**
     * This method provides getting data
     *
     * @param PaymentMethod $paymentMethod
     * @param float $amount
     *
     * @return float
     */
    public function getPaymentFee(
        PaymentMethod $paymentMethod,
        float $amount
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Tip $tip
     *
     * @return string|null
     */
    public function getPaymentUrl(
        Tip $tip
    ) : ?string;

    /**
     * This method provides creating data
     * by related entity repository with a certain query
     *
     * @param Tip $tip
     *
     * @return Tip
     */
    public function executePayment(
        Tip $tip
    ) : Tip;

    /**
     * This method provides checking data
     *
     * @param Tip $tip
     *
     * @return bool
     */
    public function checkTipUnpaid(
        Tip $tip
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param Tip $tip
     *
     * @return Tip
     */
    public function updatePaidTip(
        Tip $tip
    ) : Tip;

    /**
     * This method provides updating data
     *
     * @param Tip $tip
     *
     * @return Tip
     */
    public function cancelPayment(
        Tip $tip
    ) : Tip;
}
