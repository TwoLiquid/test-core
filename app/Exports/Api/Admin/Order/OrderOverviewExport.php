<?php

namespace App\Exports\Api\Admin\Order;

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
 * Class OrderOverviewExport
 *
 * @package App\Exports\Api\Admin\Order
 */
class OrderOverviewExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $orders;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * OrderOverviewExport constructor
     *
     * @param Collection $orders
     */
    public function __construct(
        Collection $orders
    )
    {
        /** @var Collection orders */
        $this->orders = $orders;

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
            'Order overview ID',
            'Order date',
            'Buyer',
            'Seller(s)',
            'Order Items',
            'Total ($)',
            'Vybe Type',
            'Order item payment status(es)'
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
            $this->getSellers($row),
            $this->getOrderItems($row),
            $this->getTotal($row),
            $this->getVybeTypes($row),
            $this->getOrderItemPaymentStatuses($row)
        ];
    }

    /**
     * @return Collection
     */
    public function collection() : Collection
    {
        return $this->orders;
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
     * @return string|null
     */
    protected function getOrderItems($row) : ?string
    {
        if ($row->relationLoaded('items')) {
            if (count($row->items) > 1) {
                return $row->items[0]->full_id . ' (' . count($row->items) . ')';
            }

            return $row->items[0]->full_id;
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
        return $row->amount;
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

            return implode(', ', $orderItemsPaymentStatuses->pluck('name')
                ->values()
                ->toArray()
            );
        }

        return null;
    }
}