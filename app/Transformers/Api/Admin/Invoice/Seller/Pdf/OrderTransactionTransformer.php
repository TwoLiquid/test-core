<?php

namespace App\Transformers\Api\Admin\Invoice\Seller\Pdf;

use App\Models\MySql\Order\OrderTransaction;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderTransactionTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller\Pdf
 */
class OrderTransactionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'method'
    ];

    /**
     * @param OrderTransaction $orderTransaction
     *
     * @return array
     */
    public function transform(OrderTransaction $orderTransaction) : array
    {
        return [
            'id'          => $orderTransaction->id,
            'external_id' => $orderTransaction->external_id,
            'amount'      => $orderTransaction->amount,
            'created_at'  => $orderTransaction->created_at ?
                $orderTransaction->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param OrderTransaction $orderTransaction
     *
     * @return Item|null
     */
    public function includeMethod(OrderTransaction $orderTransaction) : ?Item
    {
        $paymentMethod = null;

        if ($orderTransaction->relationLoaded('method')) {
            $paymentMethod = $orderTransaction->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
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