<?php

namespace App\Services\File;

use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Tip\TipInvoice;
use App\Services\File\Interfaces\PdfServiceInterface;
use App\Services\Order\OrderItemService;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as LaravelMpdfFacade;
use Mccarlosen\LaravelMpdf\LaravelMpdf;
use Mpdf\MpdfException;

/**
 * Class PdfService
 *
 * @package App\Services\File
 */
class PdfService implements PdfServiceInterface
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * PdfService constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return LaravelMpdf
     *
     * @throws MpdfException
     */
    public function getInvoiceForBuyer(
        OrderInvoice $orderInvoice
    ) : LaravelMpdf
    {
        return LaravelMpdfFacade::loadView('pdf.documents.invoiceForBuyer', [
            'invoice'          => $orderInvoice,
            'subtotal'         => $this->orderItemService->getPriceTotal($orderInvoice->items),
            'amount_tax_total' => $this->orderItemService->getTotalAmountTax($orderInvoice->items),
            'amount_total'     => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'payment_fee'      => $orderInvoice->order->payment_fee,
            'payment_fee_tax'  => $orderInvoice->order->payment_fee_tax
        ]);
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return LaravelMpdf
     *
     * @throws MpdfException
     */
    public function getInvoiceForSeller(
        OrderInvoice $orderInvoice
    ) : LaravelMpdf
    {
        return LaravelMpdfFacade::loadView('pdf.documents.invoiceForSeller', [
            'invoice'          => $orderInvoice,
            'seller'           => $orderInvoice->items[0]->seller,
            'subtotal'         => $this->orderItemService->getPriceTotal($orderInvoice->items),
            'amount_tax_total' => $this->orderItemService->getTotalAmountTax($orderInvoice->items),
            'amount_total'     => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'payment_fee'      => $orderInvoice->order->payment_fee,
            'payment_fee_tax'  => $orderInvoice->order->payment_fee_tax
        ]);
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return LaravelMpdf
     *
     * @throws MpdfException
     */
    public function getWithdrawalReceipt(
        WithdrawalReceipt $withdrawalReceipt
    ) : LaravelMpdf
    {
        return LaravelMpdfFacade::loadView('pdf.documents.withdrawalReceipt', [
            'receipt' => $withdrawalReceipt
        ]);
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return LaravelMpdf
     *
     * @throws MpdfException
     */
    public function getAddFundsReceipt(
        AddFundsReceipt $addFundsReceipt
    ) : LaravelMpdf
    {
        return LaravelMpdfFacade::loadView('pdf.documents.addFundsReceipt', [
            'receipt' => $addFundsReceipt
        ]);
    }

    /**
     * @param TipInvoice $tipInvoiceForBuyer
     *
     * @return LaravelMpdf
     *
     * @throws MpdfException
     */
    public function getTipInvoiceForBuyer(
        TipInvoice $tipInvoiceForBuyer
    ) : LaravelMpdf
    {
        return LaravelMpdfFacade::loadView('pdf.documents.tipInvoiceForBuyer', [
            'invoice'         =>  $tipInvoiceForBuyer,
            'subtotal'        =>  $tipInvoiceForBuyer->tip->amount,
            'amount_tax'      =>  $tipInvoiceForBuyer->tip->amount_tax,
            'payment_fee'     =>  $tipInvoiceForBuyer->tip->payment_fee,
            'payment_fee_tax' =>  $tipInvoiceForBuyer->tip->payment_fee_tax
        ]);
    }

    /**
     * @param TipInvoice $tipInvoiceForSeller
     *
     * @return LaravelMpdf
     *
     * @throws MpdfException
     */
    public function getTipInvoiceForSeller(
        TipInvoice $tipInvoiceForSeller
    ) : LaravelMpdf
    {
        return LaravelMpdfFacade::loadView('pdf.documents.tipInvoiceForSeller', [
            'invoice' =>  $tipInvoiceForSeller
        ]);
    }
}