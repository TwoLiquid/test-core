<?php

namespace App\Exports\Api\Admin\Order;

use App\Exports\BaseExport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Class TipExport
 *
 * @package App\Exports\Api\Admin\Order
 */
class TipExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $tips;

    /**
     * TipExport constructor
     *
     * @param Collection $tips
     */
    public function __construct(
        Collection $tips
    )
    {
        /** @var Collection tips */
        $this->tips = $tips;
    }

    /**
     * @return array
     */
    public function columnFormats() : array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * @return array
     */
    public function headings() : array
    {
        return [
            'Order item ID',
            'Vybe type',
            'Buyer',
            'Seller',
            'Payment method',
            'Order item status (vybe)',
            'Tip invoice for buyer ID',
            'Tip Invoice for buyer status',
            'Tip date',
            'Tip amount ($)',
            'Handling fee ($)',
            'Tip invoice for seller ID',
            'Tip Invoice for seller status'
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
            $this->getOrderItemId($row),
            $this->getVybeType($row),
            $this->getBuyer($row),
            $this->getSeller($row),
            $this->getPaymentMethod($row),
            $this->getOrderItemStatus($row),
            $this->getTipInvoiceBuyerId($row),
            $this->getTipInvoiceBuyerStatus($row),
            $this->getDate($row),
            $this->getTipAmount($row),
            $this->getHandlingFee($row),
            $this->getTipInvoiceSellerId($row),
            $this->getTipInvoiceSellerStatus($row)
        ];
    }

    /**
     * @return Collection
     */
    public function collection() : Collection
    {
        return $this->tips;
    }

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getOrderItemId($row) : ?string
    {
        if ($row->relationLoaded('item')) {
            return $row->item->full_id;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getVybeType($row) : ?string
    {
        if ($row->relationLoaded('item')) {
            $orderItem = $row->item;

            if ($orderItem->relationLoaded('appearanceCase')) {
                $appearanceCase = $orderItem->appearanceCase;

                if ($appearanceCase->relationLoaded('vybe')) {
                    return $appearanceCase->vybe
                        ->getType()
                        ->name;
                }
            }
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
        if ($row->relationLoaded('buyer')) {
            return $row->buyer->username;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getSeller($row) : ?string
    {
        if ($row->relationLoaded('seller')) {
            return $row->seller->username;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getPaymentMethod($row) : ?string
    {
        if ($row->relationLoaded('method')) {
            return $row->method->name;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getOrderItemStatus($row) : ?string
    {
        if ($row->relationLoaded('item')) {
            return $row->item->getStatus()->name;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getTipInvoiceBuyerId($row) : ?string
    {
        if ($row->relationLoaded('invoices')) {
            return $row->getBuyerInvoice()->full_id;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getTipInvoiceBuyerStatus($row) : ?string
    {
        if ($row->relationLoaded('invoices')) {
            return $row->getBuyerInvoice()->getStatus()->name;
        }

        return null;
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
     * @return float|null
     */
    protected function getTipAmount($row) : ?float
    {
        return $row->amount;
    }

    /**
     * @param $row
     *
     * @return float|null
     */
    protected function getHandlingFee($row) : ?float
    {
        return $row->handling_fee;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getTipInvoiceSellerId($row) : ?string
    {
        if ($row->relationLoaded('invoices')) {
            return $row->getSellerInvoice()->full_id;
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getTipInvoiceSellerStatus($row) : ?string
    {
        if ($row->relationLoaded('invoices')) {
            return $row->getSellerInvoice()->getStatus()->name;
        }

        return null;
    }
}