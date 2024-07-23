<?php

namespace App\Exports\Api\Admin\User\Finance\Seller;

use App\Exports\BaseExport;
use App\Services\Order\OrderItemService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Class InvoiceExport
 *
 * @package App\Exports\Api\Admin\User\Finance\Seller
 */
class InvoiceExport extends BaseExport implements FromCollection, WithHeadings,
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
     * InvoiceExport constructor
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
            'G' => NumberFormat::FORMAT_TEXT
        ];
    }

    /**
     * @return array
     */
    public function headings() : array
    {
        return [
            'Invoice for seller ID',
            'Date',
            'Buyer',
            'Earned ($)',
            'Vybe type',
            'Order item status',
            'Invoice for seller status'
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
            $this->getBuyer($row),
            $this->getEarned($row),
            $this->getVybeType($row),
            $this->getOrderItemStatus($row),
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
     * @return float|null
     */
    protected function getEarned($row) : ?float
    {
        if ($row->relationLoaded('items')) {
            return $this->orderItemService->getTotalAmountEarned($row->items);
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
        if ($row->relationLoaded('items')) {
            $orderItems = $row->items;

            if (isset($orderItems[0])) {
                if ($orderItems[0]->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderItems[0]->appearanceCase;

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
    protected function getOrderItemStatus($row) : ?string
    {
        if ($row->relationLoaded('items')) {
            $orderItems = $row->items;

            if (isset($orderItems[0])) {
                return $orderItems[0]->getStatus()->name;
            }
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