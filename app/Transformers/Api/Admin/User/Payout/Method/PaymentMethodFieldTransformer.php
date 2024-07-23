<?php

namespace App\Transformers\Api\Admin\User\Payout\Method;

use App\Models\MySql\Payment\PaymentMethodField;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class PaymentMethodFieldTransformer
 *
 * @package App\Transformers\Api\Admin\User\Payout\Method
 */
class PaymentMethodFieldTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'type'
    ];

    /**
     * @param PaymentMethodField $paymentMethodField
     *
     * @return array
     */
    public function transform(PaymentMethodField $paymentMethodField) : array
    {
        return [
            'id'          => $paymentMethodField->id,
            'title'       => $paymentMethodField->title,
            'placeholder' => $paymentMethodField->placeholder,
            'tooltip'     => $paymentMethodField->tooltip
        ];
    }

    /**
     * @param PaymentMethodField $paymentMethodField
     *
     * @return Item|null
     */
    public function includeType(PaymentMethodField $paymentMethodField) : ?Item
    {
        $fieldType = $paymentMethodField->getType();

        return $fieldType ? $this->item($fieldType, new PaymentMethodFieldTypeTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method_field';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_method_fields';
    }
}
