<?php

namespace App\Transformers\Api\Admin\Invoice\Tip\Seller;

use App\Models\MySql\Payment\PaymentMethod;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentMethodTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Tip\Seller
 */
class PaymentMethodTransformer extends BaseTransformer
{
    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return array
     */
    public function transform(PaymentMethod $paymentMethod) : array
    {
        return [
            'id'   => $paymentMethod->id,
            'name' => $paymentMethod->name,
            'code' => $paymentMethod->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_methods';
    }
}
