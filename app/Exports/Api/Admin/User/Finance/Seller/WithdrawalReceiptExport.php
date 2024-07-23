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
 * Class WithdrawalReceiptExport
 *
 * @package App\Exports\Api\Admin\User\Finance\Seller
 */
class WithdrawalReceiptExport extends BaseExport implements FromCollection, WithHeadings,
    WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var Collection
     */
    protected Collection $withdrawalReceipts;

    /**
     * WithdrawalReceiptExport constructor
     *
     * @param Collection $withdrawalReceipts
     */
    public function __construct(
        Collection $withdrawalReceipts
    )
    {
        /** @var Collection withdrawalReceipts */
        $this->withdrawalReceipts = $withdrawalReceipts;
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
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    /**
     * @return array
     */
    public function headings() : array
    {
        return [
            'Receipt ID',
            'Request ID',
            'Payout method',
            'Total ($)',
            'Withdrawal receipt status',
            'Payout / credit date'
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
            $this->getReceiptId($row),
            $this->getRequestId($row),
            $this->getDate($row),
            $this->getPayoutMethod($row),
            $this->getTotal($row),
            $this->getPayoutStatus($row)
        ];
    }

    /**
     * @return Collection
     */
    public function collection() : Collection
    {
        return $this->withdrawalReceipts;
    }

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @param $row
     *
     * @return string
     */
    protected function getReceiptId($row) : string
    {
        return $row->full_id;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getRequestId($row) : ?string
    {
        if ($row->relationLoaded('request')) {
            if ($row->request) {
                return $row->request->_id;
            }
        }

        return null;
    }

    /**
     * @param $row
     *
     * @return string|null
     */
    protected function getPayoutMethod($row) : ?string
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
    protected function getTotal($row) : float
    {
        return $row->amount;
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function getPayoutStatus($row) : string
    {
        return $row->getStatus()->name;
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
}