<?php

namespace App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest;

use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalReceiptListTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\Withdrawal\Receipt
 */
class WithdrawalReceiptListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'method',
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
            'id'           => $withdrawalReceipt->id,
            'description'  => $withdrawalReceipt->description,
            'amount'       => $withdrawalReceipt->amount,
            'payment_fee'  => $withdrawalReceipt->payment_fee,
            'amount_total' => $withdrawalReceipt->amount_total,
            'created_at'   => $withdrawalReceipt->created_at ?
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
