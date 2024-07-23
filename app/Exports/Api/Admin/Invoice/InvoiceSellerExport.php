<?php

namespace App\Exports\Api\Admin\Invoice;

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
 * Class InvoiceSellerExport
 *
 * @package App\Exports\Api\Admin\Invoice
 */
class InvoiceSellerExport extends BaseExport implements FromCollection, WithHeadings,
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
     * InvoiceSellerExport constructor
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
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT
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
            'Vybe version',
            'Seller',
            'Buyer',
            'Total ($)',
            'Handling fee ($)',
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
            $this->getVybeVersion($row),
            $this->getSeller($row),
            $this->getBuyer($row),
            $this->getTotal($row),
            $this->getHandlingFee($row),
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
    protected function getVybeVersion($row) : ?string
    {
        if ($row->relationLoaded('items')) {
            $orderItems = $row->items;

            if (isset($orderItems[0])) {
                if ($orderItems[0]->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderItems[0]->appearanceCase;

                    if ($appearanceCase->relationLoaded('vybe')) {
                        return $appearanceCase->vybe->full_version;
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
    protected function getSeller($row) : ?string
    {
        if ($row->relationLoaded('items')) {
            $orderItems = $row->items;

            if (isset($orderItems[0])) {
                if ($orderItems[0]->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderItems[0]->appearanceCase;

                    if ($appearanceCase->relationLoaded('vybe')) {
                        $vybe = $appearanceCase->vybe;

                        if ($vybe->relationLoaded('user')) {
                            return $vybe->user->username;
                        }
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