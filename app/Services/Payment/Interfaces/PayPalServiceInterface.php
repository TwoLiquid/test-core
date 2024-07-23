<?php

namespace App\Services\Payment\Interfaces;

use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Tip\Tip;
use App\Support\Service\PayPal\TransactionResponse;

/**
 * Interface PayPalServiceInterface
 *
 * @package App\Services\Payment\Interfaces
 */
interface PayPalServiceInterface
{
    /**
     * This method provides creating data
     *
     * @param OrderInvoice $orderInvoice
     *
     * @return string
     */
    public function getOrderPaymentUrl(
        OrderInvoice $orderInvoice
    ) : string;

    /**
     * This method provides creating data
     *
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return string
     */
    public function getAddFundsPaymentUrl(
        AddFundsReceipt $addFundsReceipt
    ) : string;

    /**
     * This method provides creating data
     *
     * @param Tip $tip
     *
     * @return string
     */
    public function getTipPaymentUrl(
        Tip $tip
    ) : string;

    /**
     * This method provides creating data
     * by related entity repository with a certain query
     *
     * @param string $paymentId
     * @param string $payerId
     *
     * @return TransactionResponse|null
     */
    public function executePayment(
        string $paymentId,
        string $payerId
    ) : ?TransactionResponse;
}
