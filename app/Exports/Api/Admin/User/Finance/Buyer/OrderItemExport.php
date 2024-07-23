<?php

namespace App\Exports\Api\Admin\User\Finance\Buyer;

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
 * Class OrderItemExport
 *
 * @package App\Exports\Api\Admin\User\Finance\Buyer
 */
class OrderItemExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $orderItems;

    /**
     * OrderItemExport constructor
     *
     * @param Collection $orderItems
     */
    public function __construct(
        Collection $orderItems
    )
    {
        /** @var Collection orderItems */
        $this->orderItems = $orderItems;
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
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
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
            'Order date',
            'Seller',
            'Total ($)',
            'Vybe version',
            'Vybe type',
            'Order item status',
            'Order item payment status'
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
            $this->getSeller($row),
            $this->getTotal($row),
            $this->getVybeVersion($row),
            $this->getVybeType($row),
            $this->getOrderItemStatus($row),
            $this->getOrderItemPaymentStatus($row)
        ];
    }

    /**
     * @return Collection
     */
    public function collection() : Collection
    {
        return $this->orderItems;
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
    protected function getSeller($row) : ?string
    {
        if ($row->relationLoaded('appearanceCase')) {
            $appearanceCase = $row->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybe = $appearanceCase->vybe;

                if ($vybe->relationLoaded('user')) {
                    return $vybe->user->username;
                }
            }
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return float
     */
    protected function getTotal($row) : float
    {
        return $row->amount_total;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getVybeVersion($row) : ?string
    {
        if ($row->relationLoaded('appearanceCase')) {
            $appearanceCase = $row->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                return $appearanceCase->vybe->full_id . '-' . $row->vybe_version;
            }
        }

        return true;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getVybeType($row) : ?string
    {
        if ($row->relationLoaded('appearanceCase')) {
            $appearanceCase = $row->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                return $appearanceCase->vybe
                    ->getType()
                    ->name;
            }
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function getOrderItemStatus($row) : string
    {
        return $row->getStatus()->name;
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function getOrderItemPaymentStatus($row) : string
    {
        return $row->getPaymentStatus()->name;
    }
}