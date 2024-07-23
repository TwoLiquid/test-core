<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds;

use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class AddFundsReceiptTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds
 */
class AddFundsReceiptTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'method',
        'status'
    ];

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return array
     */
    public function transform(AddFundsReceipt $addFundsReceipt) : array
    {
        return [
            'id'           => $addFundsReceipt->id,
            'prefix'       => 'AF',
            'full_id'      => $addFundsReceipt->full_id,
            'amount'       => $addFundsReceipt->amount,
            'payment_fee'  => $addFundsReceipt->payment_fee,
            'amount_total' => $addFundsReceipt->amount_total,
            'created_at'   => $addFundsReceipt->created_at ?
                $addFundsReceipt->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return Item|null
     */
    public function includeUser(AddFundsReceipt $addFundsReceipt) : ?Item
    {
        $user = null;

        if ($addFundsReceipt->relationLoaded('user')) {
            $user = $addFundsReceipt->user;
        }

        return $user ? $this->item($user, new UserShortTransformer) : null;
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return Item|null
     */
    public function includeMethod(AddFundsReceipt $addFundsReceipt) : ?Item
    {
        $paymentMethod = null;

        if ($addFundsReceipt->relationLoaded('method')) {
            $paymentMethod = $addFundsReceipt->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return Item|null
     */
    public function includeStatus(AddFundsReceipt $addFundsReceipt) : ?Item
    {
        $status = $addFundsReceipt->getStatus();

        return $status ? $this->item($status, new AddFundsReceiptStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'add_funds_receipt';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'add_funds_receipts';
    }
}
