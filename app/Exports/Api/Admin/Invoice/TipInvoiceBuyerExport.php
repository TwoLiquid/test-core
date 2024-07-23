<?php

namespace App\Exports\Api\Admin\Invoice;

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
 * Class TipInvoiceBuyerExport
 *
 * @package App\Exports\Api\Admin\Invoice
 */
class TipInvoiceBuyerExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $tipInvoices;

    /**
     * TipInvoiceBuyerExport constructor
     *
     * @param Collection $tipInvoices
     */
    public function __construct(
        Collection $tipInvoices
    )
    {
        /** @var Collection tipInvoices */
        $this->tipInvoices = $tipInvoices;
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
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_TEXT
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
            'Date',
            'Price ($)',
            'Amount ($)',
            'Handling fee ($)',
            'Order item status',
            'Tip invoice for buyer ID',
            'Tip amount ($)',
            'Tip Invoice for buyer status'
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
            $this->getDate($row),
            $this->getPrice($row),
            $this->getAmount($row),
            $this->getHandlingFee($row),
            $this->getOrderItemStatus($row),
            $this->getTipInvoiceId($row),
            $this->getTipAmount($row),
            $this->getInvoiceStatus($row)
        ];
    }

    /**
     * @return Collection
     */
    public function collection() : Collection
    {
        return $this->tipInvoices;
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
        if ($row->relationLoaded('tip')) {
            $tip = $row->tip;

            if ($tip->relationLoaded('item')) {
                return $tip->item->full_id;
            }
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
        if ($row->relationLoaded('tip')) {
            $tip = $row->tip;

            if ($tip->relationLoaded('item')) {
                $orderItem = $tip->item;

                if ($orderItem->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderItem->appearanceCase;

                    if ($appearanceCase->relationLoaded('vybe')) {
                        return $appearanceCase->vybe
                            ->getType()
                            ->name;
                    }
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
        if ($row->relationLoaded('tip')) {
            $tip = $row->tip;

            if ($tip->relationLoaded('buyer')) {
                return $tip->buyer->username;
            }
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
        if ($row->relationLoaded('tip')) {
            $tip = $row->tip;

            if ($tip->relationLoaded('seller')) {
                return $tip->seller->username;
            }
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
    protected function getPrice($row) : ?float
    {
        if ($row->relationLoaded('tip')) {
            $tip = $row->tip;

            if ($tip->relationLoaded('item')) {
                $orderItem = $tip->item;

                if ($orderItem->relationLoaded('appearanceCase')) {
                    return $orderItem->appearanceCase->price;
                }
            }
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return float|null
     */
    protected function getAmount($row) : ?float
    {
        if ($row->relationLoaded('tip')) {
            $tip = $row->tip;

            if ($tip->relationLoaded('item')) {
                return $tip->item->amount_total;
            }
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
        if ($row->relationLoaded('tip')) {
            return $row->tip->handling_fee;
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
        if ($row->relationLoaded('tip')) {
            $tip = $row->tip;

            if ($tip->relationLoaded('item')) {
                return $tip->item->getStatus()->name;
            }
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function getTipInvoiceId($row) : string
    {
        return $row->full_id;
    }

    /**
     * @param $row
     *
     * @return float|null
     */
    protected function getTipAmount($row) : ?float
    {
        if ($row->relationLoaded('tip')) {
            return $row->tip->amount;
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