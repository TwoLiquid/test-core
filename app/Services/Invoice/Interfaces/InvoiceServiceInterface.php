<?php

namespace App\Services\Invoice\Interfaces;

use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface InvoiceServiceInterface
 *
 * @package App\Services\Invoice\Interfaces
 */
interface InvoiceServiceInterface
{
    /**
     * This method provides creating data
     * by related entity repository
     *
     * @param Order $order
     *
     * @return OrderInvoice
     */
    public function createForBuyer(
        Order $order
    ) : OrderInvoice;

    /**
     * This method provides creating data
     * by related entity repository
     *
     * @param OrderInvoice $buyerInvoice
     * @param OrderItem $orderItem
     *
     * @return OrderInvoice
     */
    public function createCreditForBuyer(
        OrderInvoice $buyerInvoice,
        OrderItem $orderItem
    ) : OrderInvoice;

    /**
     * This method provides creating data
     * by related entity repository
     *
     * @param Order $order
     *
     * @return Collection
     */
    public function createForSellers(
        Order $order
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForBuyerFromOrder(
        Collection $orderInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForSellerFromOrder(
        Collection $orderInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param OrderItem $orderItem
     *
     * @return Collection
     */
    public function getUniqueStatusesFromOrderItem(
        OrderItem $orderItem
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForAdminBuyerStatusesByIds(
        Collection $orderInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForAdminSellerStatusesByIds(
        Collection $orderInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getByOrderItemsForAdminSellerStatusesByIds(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param User $user
     * @param OrderInvoice $orderInvoice
     *
     * @return bool
     */
    public function belongsToSeller(
        User $user,
        OrderInvoice $orderInvoice
    ) : bool;

    /**
     * This method provides creating data
     *
     * @param Collection $orderInvoices
     * @param string $code
     */
    public function addSellerTransactionLogs(
        Collection $orderInvoices,
        string $code
    ) : void;
}