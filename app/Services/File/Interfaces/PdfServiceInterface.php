<?php

namespace App\Services\File\Interfaces;

use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Tip\TipInvoice;
use Mccarlosen\LaravelMpdf\LaravelMpdf;

/**
 * Interface PdfServiceInterface
 *
 * @package App\Services\File\Interfaces
 */
interface PdfServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param OrderInvoice $orderInvoice
     *
     * @return LaravelMpdf
     */
    public function getInvoiceForBuyer(
        OrderInvoice $orderInvoice
    ) : LaravelMpdf;

    /**
     * This method provides getting data
     *
     * @param OrderInvoice $orderInvoice
     *
     * @return LaravelMpdf
     */
    public function getInvoiceForSeller(
        OrderInvoice $orderInvoice
    ) : LaravelMpdf;

    /**
     * This method provides getting data
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * 
     * @return LaravelMpdf
     */
    public function getWithdrawalReceipt(
        WithdrawalReceipt $withdrawalReceipt
    ) : LaravelMpdf;

    /**
     * This method provides getting data
     *
     * @param AddFundsReceipt $addFundsReceipt
     * 
     * @return LaravelMpdf
     */
    public function getAddFundsReceipt(
        AddFundsReceipt $addFundsReceipt
    ) : LaravelMpdf;
    
    /**
     * This method provides getting data
     *
     * @param TipInvoice $tipInvoiceForBuyer
     * 
     * @return LaravelMpdf
     */
    public function getTipInvoiceForBuyer(
        TipInvoice $tipInvoiceForBuyer
    ) : LaravelMpdf;

    /**
     * @param TipInvoice $tipInvoiceForSeller
     *
     * @return LaravelMpdf
     */
    public function getTipInvoiceForSeller(
        TipInvoice $tipInvoiceForSeller
    ) : LaravelMpdf;
}