<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal;

use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalReceiptTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class WithdrawalReceiptTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'method',
        'request',
        'status'
    ];

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return array
     */
    public function transform(WithdrawalReceipt $withdrawalReceipt) : array
    {
        return [
            'id'         => $withdrawalReceipt->id,
            'prefix'     => 'WR',
            'full_id'    => $withdrawalReceipt->full_id,
            'amount'     => $withdrawalReceipt->amount,
            'created_at' => $withdrawalReceipt->created_at ?
                $withdrawalReceipt->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Item|null
     */
    public function includeMethod(WithdrawalReceipt $withdrawalReceipt) : ?Item
    {
        $payoutMethod = null;

        if ($withdrawalReceipt->relationLoaded('method')) {
            $payoutMethod = $withdrawalReceipt->method;
        }

        return $payoutMethod ? $this->item($payoutMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Item|null
     */
    public function includeRequest(WithdrawalReceipt $withdrawalReceipt) : ?Item
    {
        $withdrawalRequest = null;

        if ($withdrawalReceipt->relationLoaded('request')) {
            $withdrawalRequest = $withdrawalReceipt->request;
        }

        return $withdrawalRequest ? $this->item($withdrawalRequest, new WithdrawalRequestTransformer) : null;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Item|null
     */
    public function includeStatus(WithdrawalReceipt $withdrawalReceipt) : ?Item
    {
        $status = $withdrawalReceipt->getStatus();

        return $status ? $this->item($status, new WithdrawalReceiptStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_receipt';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_receipts';
    }
}
