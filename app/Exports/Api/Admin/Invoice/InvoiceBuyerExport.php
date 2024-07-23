<?php

namespace App\Exports\Api\Admin\Invoice;

use App\Exports\BaseExport;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Class InvoiceBuyerExport
 *
 * @package App\Exports\Api\Admin\Invoice
 */
class InvoiceBuyerExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $invoices;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * InvoiceBuyerExport constructor
     *
     * @param Collection $invoices
     */
    public function __construct(
        Collection $invoices
    )
    {
        /** @var Collection invoices */
        $this->invoices = $invoices;

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var OrderService orderService */
        $this->orderService = new OrderService();
    }

    /**
     * @return array
     */
    public function columnFormats() : array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT
        ];
    }

    /**
     * @return array
     */
    public function headings() : array
    {
        return [
            'Invoice for buyer ID',
            'Date',
            'Order overview ID',
            'Buyer',
            'Seller(s)',
            'Total ($)',
            'Handling fee ($)',
            'Vybe type',
            'Order item payment status',
            'Invoice for buyer status'
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row) : array
    {
        return [
            $this->getId($row),
            $this->getDate($row),
            $this->getOrderOverviewId($row),
            $this->getBuyer($row),
            $this->getSellers($row),
            $this->getTotal($row),
            $this->getHandlingFee($row),
            $this->getVybeTypes($row),
            $this->getOrderItemPaymentStatuses($row),
            $this->getInvoiceStatus($row)
        ];
    }

    /**
     * @return Collection
     */
    public function collection() : Collection
    {
        return $this->invoices;
    }

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @param $row
     *
     * @return string
     */
    protected function getId($row) : string
    {
        return $row->full_id;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getDate($row) : ?string
    {
        return $row->created_at ?
            Carbon::parse($row->created_at)->format('d.m.Y') :
            null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getOrderOverviewId($row) : ?string
    {
        if ($row->relationLoaded('order')) {
            return $row->order->full_id;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getBuyer($row) : ?string
    {
        if ($row->relationLoaded('order')) {
            $order = $row->order;

            if ($order->relationLoaded('buyer')) {
                return $order->buyer->username;
            }
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getSellers($row) : ?string
    {
        if ($row->relationLoaded('items')) {
            $sellers = $this->orderService->getUniqueSellers($row->items);

            if (count($sellers) > 1) {
                return $sellers[0]->username . ' (' . count($sellers) . ')';
            }

            return $sellers[0]->username;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return float|null
     */
    protected function getTotal($row) : ?float
    {
        if ($row->relationLoaded('items')) {
            return $this->orderItemService->getAmountTotal(
                $row->items
            );
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return float|null
     */
    protected function getHandlingFee($row) : ?float
    {
        if ($row->relationLoaded('items')) {
            return $this->orderItemService->getTotalHandlingFee($row->items);
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getVybeTypes($row) : ?string
    {
        if ($row->relationLoaded('items')) {
            $vybeTypes = $this->orderItemService->getVybesTypes(
                $row->items
            );

            return implode(', ', $vybeTypes->pluck('name')
                ->values()
                ->toArray()
            );
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getOrderItemPaymentStatuses($row) : ?string
    {
        if ($row->relationLoaded('items')) {
            $orderItemsPaymentStatuses = $this->orderItemService->getOrderItemsPaymentStatuses(
                $row->items
            );

            if (count($orderItemsPaymentStatuses) > 1) {
                return $orderItemsPaymentStatuses[0]->name . ' (' . count($orderItemsPaymentStatuses) . ')';
            }

            return $orderItemsPaymentStatuses[0]->name;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function getInvoiceStatus($row) : string
    {
        return $row->getStatus()->name;
    }
}