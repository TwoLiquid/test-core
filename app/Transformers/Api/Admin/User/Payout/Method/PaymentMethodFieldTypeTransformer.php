<?php

namespace App\Transformers\Api\Admin\User\Payout\Method;

use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentMethodFieldTypeTransformer
 *
 * @package App\Transformers\Api\Admin\User\Payout\Method
 */
class PaymentMethodFieldTypeTransformer extends BaseTransformer
{
    /**
     * @param PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem
     *
     * @return array
     */
    public function transform(PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem) : array
    {
        return [
            'id'   => $paymentMethodFieldTypeListItem->id,
            'name' => $paymentMethodFieldTypeListItem->name,
            'code' => $paymentMethodFieldTypeListItem->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method_field_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_method_field_types';
    }
}
