<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\PaymentMethodImageResponse;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentMethodImageTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class PaymentMethodImageTransformer extends BaseTransformer
{
    /**
     * @param PaymentMethodImageResponse $paymentMethodImageResponse
     *
     * @return array
     */
    public function transform(PaymentMethodImageResponse $paymentMethodImageResponse) : array
    {
        return [
            'id'        => $paymentMethodImageResponse->id,
            'method_id' => $paymentMethodImageResponse->methodId,
            'url'       => $paymentMethodImageResponse->url,
            'url_min'   => $paymentMethodImageResponse->urlMin,
            'mime'      => $paymentMethodImageResponse->mime
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
