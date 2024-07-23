<?php

namespace App\Transformers\Api\Admin\General\Payment;

use App\Models\MySql\Payment\PaymentMethodField;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class PaymentMethodFieldTransformer
 *
 * @package App\Transformers\Api\Admin\General\Payment
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
            'title'       => $paymentMethodField->getTranslations('title'),
            'placeholder' => $paymentMethodField->getTranslations('placeholder'),
            'tooltip'     => $paymentMethodField->getTranslations('tooltip')
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
