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
 * Class AddFundsReceiptExport
 *
 * @package App\Exports\Api\Admin\Invoice
 */
class AddFundsReceiptExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $addFundsReceipts;

    /**
     * AddFundsReceiptExport constructor
     *
     * @param Collection $addFundsReceipts
     */
    public function __construct(
        Collection $addFundsReceipts
    )
    {
        /** @var Collection addFundsReceipts */
        $this->addFundsReceipts = $addFundsReceipts;
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
            'F' => NumberFormat::FORMAT_NUMBER_00,
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
            'Add funds receipt ID',
            'Date',
            'Buyer',
            'Payment method',
            'Amount ($)',
            'Payment fee ($)',
            'Total amount ($)',
            'Payment status'
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
            $this->getPaymentMethod($row),
            $this->getAmount($row),
            $this->getPaymentFee($row),
            $this->getAmountTotal($row),
            $this->getPaymentStatus($row)
        ];
    }

    /**
     * @return Collection
     */
    public function collection() : Collection
    {
        return $this->addFundsReceipts;
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
        if ($row->relationLoaded('user')) {
            return $row->user->username;
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
     * @return float
     */
    protected function getAmount($row) : float
    {
        return $row->amount;
    }

    /**
     * @param $row
     *
     * @return float|null
     */
    protected function getPaymentFee($row) : ?float
    {
        return $row->payment_fee;
    }

    /**
     * @param $row
     *
     * @return float|null
     */
    protected function getAmountTotal($row) : ?float
    {
        return $row->amount_total;
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function getPaymentStatus($row) : string
    {
        return $row->getStatus()->name;
    }
}