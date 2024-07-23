<?php

namespace App\Services\Order\Interfaces;

use App\Models\MySql\Order\Order;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrderServiceInterface
 *
 * @package App\Services\Order\Interfaces
 */
interface OrderServiceInterface
{
    /**
     * This method provides creating data
     * by related entity repository
     *
     * @param User $buyer
     * @param PaymentMethod $paymentMethod
     * @param array $orderItems
     *
     * @return Order|null
     */
    public function createOrder(
        User $buyer,
        PaymentMethod $paymentMethod,
        array $orderItems
    ) : ?Order;

    /**
     * This method provides checking data
     *
     * @param Order $order
     * @param string $hash
     *
     * @return bool
     */
    public function checkHash(
        Order $order,
        string $hash
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Order $order
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getAmountTotal(
        Order $order,
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param PaymentMethod $paymentMethod
     * @param float|null $amount
     *
     * @return float
     */
    public function getPaymentFee(
        PaymentMethod $paymentMethod,
        ?float $amount
    ) : float;

    /**
     * This method provides getting data
     * by related entity repository with a certain query
     *
     * @param Order $order
     *
     * @return string|null
     */
    public function getPaymentUrl(
        Order $order
    ) : ?string;

    /**
     * This method provides creating data
     * by related entity repository with a certain query
     *
     * @param Order $order
     *
     * @return Order
     */
    public function executePayment(
        Order $order
    ) : Order;

    /**
     * This method provides updating data
     * by related entity repository with a certain query
     *
     * @param Order $order
     *
     * @return Order
     */
    public function cancelPayment(
        Order $order
    ) : Order;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param Order $order
     *
     * @return Order
     */
    public function updatePaidOrder(
        Order $order
    ) : Order;

    /**
     * This method provides getting data
     *
     * @param Order $order
     *
     * @return float
     */
    public function getItemsTotalHandlingFee(
        Order $order
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getUniqueSellersCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getUniqueSellers(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getUniqueBuyersCount(
        Collection $orderItems
    ) : int;

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getUniqueBuyers(
        Collection $orderItems
    ) : Collection;
}
