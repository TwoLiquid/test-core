<?php

namespace App\Exports\Api\Admin\User\Finance\Seller;

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
 * Class SaleOverviewExport
 *
 * @package App\Exports\Api\Admin\User\Finance\Seller
 */
class SaleOverviewExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $sales;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * SaleOverviewExport constructor
     *
     * @param Collection $sales
     */
    public function __construct(
        Collection $sales
    )
    {
        /** @var Collection sales */
        $this->sales = $sales;

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
            'E' => NumberFormat::FORMAT_NUMBER_00,
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
            'Sale overview ID',
            'Order date',
            'Buyer',
            'Order Items',
            'Total ($)',
            'Vybe type',
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
            $this->getBuyer($row),
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
        return $this->sales;
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
        if ($row->relationLoaded('items')) {
            $buyers = $this->orderService->getUniqueBuyers($row->items);

            if (count($buyers) > 1) {
                return $buyers[0]->username . ' (' . count($buyers) . ')';
            }

            return $buyers[0]->username;
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
        return $row->amount_total;
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
}