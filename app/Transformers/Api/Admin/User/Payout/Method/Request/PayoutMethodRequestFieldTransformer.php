<?php

namespace App\Transformers\Api\Admin\User\Payout\Method\Request;

use App\Models\MongoDb\Payout\PayoutMethodRequestField;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class PayoutMethodRequestFieldTransformer
 *
 * @package App\Transformers\Api\Admin\User\Payout\Method\Request
 */
class PayoutMethodRequestFieldTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'type'
    ];

    /**
     * @param PayoutMethodRequestField $payoutMethodRequestField
     *
     * @return array
     */
    public function transform(PayoutMethodRequestField $payoutMethodRequestField) : array
    {
        return [
            'id'          => $payoutMethodRequestField->_id,
            'field_id'    => $payoutMethodRequestField->field->id,
            'title'       => $payoutMethodRequestField->field->title,
            'placeholder' => $payoutMethodRequestField->field->placeholder,
            'tooltip'     => $payoutMethodRequestField->field->tooltip,
            'value'       => $payoutMethodRequestField->value
        ];
    }

    /**
     * @param PayoutMethodRequestField $payoutMethodRequestField
     *
     * @return Item|null
     */
    public function includeType(PayoutMethodRequestField $payoutMethodRequestField) : ?Item
    {
        $payoutMethodFieldType = null;

        if ($payoutMethodRequestField->relationLoaded('field')) {
            $payoutMethodFieldType = $payoutMethodRequestField->field->getType();
        }

        return $payoutMethodFieldType ? $this->item($payoutMethodFieldType, new PaymentMethodFieldTypeTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payout_method_request_field';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payout_method_request_fields';
    }
}
