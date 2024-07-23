<?php

namespace App\Transformers\Api\Guest\Payment\Method\Field\Type;

use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class FieldTypeTransformer
 *
 * @package App\Transformers\Api\Guest\Payment\Method\FieldType
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
            'code' => $paymentMethodFieldTypeListItem->code,
            'name' => $paymentMethodFieldTypeListItem->name
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
