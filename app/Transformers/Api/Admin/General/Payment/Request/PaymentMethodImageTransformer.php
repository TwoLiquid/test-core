<?php

namespace App\Transformers\Api\Admin\General\Payment\Request;

use App\Models\MySql\Media\PaymentMethodImage;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentMethodImageTransformer
 *
 * @package App\Transformers\Api\Admin\General\Payment\Request
 */
class PaymentMethodImageTransformer extends BaseTransformer
{
    /**
     * @param PaymentMethodImage $paymentMethodImage
     *
     * @return array
     */
    public function transform(PaymentMethodImage $paymentMethodImage) : array
    {
        return [
            'id'      => $paymentMethodImage->id,
            'url'     => $paymentMethodImage->url,
            'url_min' => $paymentMethodImage->url_min,
            'mime'    => $paymentMethodImage->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_method_images';
    }
}
