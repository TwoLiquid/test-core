<?php

namespace App\Services\Order\Interfaces;

use App\Models\MySql\Billing;
use App\Models\MySql\Order\Order;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrderItemServiceInterface
 *
 * @package App\Services\Order\Interfaces
 */
interface OrderItemServiceInterface
{
    /**
     * This method provides creating data
     * by related entity repository with a certain query
     *
     * @param Order $order
     * @param array $orderItems
     *
     * @return Collection
     */
    public function createOrderItems(
        Order $order,
        array $orderItems
    ) : Collection;

    /**
     * This method provides creating data
     * by related entity repository with a certain query
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function createPendingRequests(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides updating data
     * by related entity repository with a certain query
     *
     * @param Order $order
     *
     * @return Collection
     */
    public function processPaidOrderItems(
        Order $order
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param User $buyer
     * @param array $orderItems
     *
     * @return float
     */
    public function getPreAmountTotal(
        User $buyer,
        array $orderItems
    ) : float;

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
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getVybesTypes(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getOrderItemsStatuses(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getOrderItemsPaymentStatuses(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getAmountTotal(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getPriceTotal(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getTotalHandlingFee(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getTotalAmountEarned(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getTotalAmountTax(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orders
     *
     * @return Collection
     */
    public function getForAdminPaymentStatusesByOrdersIds(
        Collection $orders
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getForAdminPaymentStatusesByIds(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getForAdminStatusesByIds(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForAdminPaymentStatusesByOrderInvoicesIds(
        Collection $orderInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForAdminStatusesByOrderInvoicesIds(
        Collection $orderInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $sales
     *
     * @return Collection
     */
    public function getForAdminPaymentStatusesBySalesIds(
        Collection $sales
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $tipInvoices
     *
     * @return Collection
     */
    public function getForAdminStatusesByTipInvoicesIds(
        Collection $tipInvoices
    ) : Collection;

    /**
     * This method provides sending data
     *
     * @param Collection $orderItems
     *
     * @return void
     */
    public function sendToAllSellers(
        Collection $orderItems
    ) : void;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getAcceptAverageSeconds(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getTotalCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getCanceledCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getCanceledPercentage(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getExpiredCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getExpiredPercentage(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getDisputedCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getDisputedPercentage(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getCanceledDisputeCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getCanceledDisputePercentage(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getFinishedDisputeCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getFinishedDisputePercentage(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getPartialRefundDisputeCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getPartialRefundDisputePercentage(
        Collection $orderItems
    ) : float;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getFinishedCount(
        Collection $orderItems
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getFinishedPercentage(
        Collection $orderItems
    ) : float;
}
