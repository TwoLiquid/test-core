<?php

namespace App\Exports\Api\Admin\User\Finance\Seller;

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
 * Class TipInvoiceExport
 *
 * @package App\Exports\Api\Admin\User\Finance\Seller
 */
class TipInvoiceExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $tipInvoices;

    /**
     * TipInvoiceExport constructor
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
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_TEXT
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
            'Order item status',
            'Tip invoice for seller ID',
            'Date',
            'Tip amount ($)',
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
            $this->getOrderItemStatus($row),
            $this->getTipInvoiceId($row),
            $this->getDate($row),
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