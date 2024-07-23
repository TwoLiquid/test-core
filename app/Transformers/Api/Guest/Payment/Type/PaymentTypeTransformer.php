<?php

namespace App\Transformers\Api\Guest\Payment\Type;

use App\Lists\Payment\Type\PaymentTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentTypeTransformer
 *
 * @package App\Transformers\Api\Guest\Payment\Type
 */
class PaymentTypeTransformer extends BaseTransformer
{
    /**
     * @param PaymentTypeListItem $paymentTypeListItem
     *
     * @return array
     */
    public function transform(PaymentTypeListItem $paymentTypeListItem) : array
    {
        return [
            'id'   => $paymentTypeListItem->id,
            'code' => $paymentTypeListItem->code,
            'name' => $paymentTypeListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_types';
    }
}
