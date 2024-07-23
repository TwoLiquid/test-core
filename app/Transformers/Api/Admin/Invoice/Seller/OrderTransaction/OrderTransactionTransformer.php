<?php

namespace App\Transformers\Api\Admin\Invoice\Seller\OrderTransaction;

use App\Models\MySql\Order\OrderTransaction;
use App\Transformers\BaseTransformer;

/**
 * Class OrderTransactionTransformer
 * 
 * @package App\Transformers\Api\Admin\Invoice\Seller\OrderTransaction
 */
class OrderTransactionTransformer extends BaseTransformer
{
    /**
     * @param OrderTransaction $orderTransaction
     *
     * @return array
     */
    public function transform(OrderTransaction $orderTransaction) : array
    {
        return [
            'id'              => $orderTransaction->id,
            'external_id'     => $orderTransaction->external_id,
            'amount'          => $orderTransaction->amount,
            'transaction_fee' => $orderTransaction->transaction_fee,
            'description'     => $orderTransaction->description
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'transaction';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'transactions';
    }
}
