<?php

namespace App\Transformers\Api\General\Setting\Payout\Method\Request;

use App\Lists\Payment\Method\Payment\Status\PaymentStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentStatusTransformer
 *
 * @package App\Transformers\Api\General\Setting\Payout\Method\Request
 */
class PaymentStatusTransformer extends BaseTransformer
{
    /**
     * @param PaymentStatusListItem $paymentStatusListItem
     *
     * @return array
     */
    public function transform(PaymentStatusListItem $paymentStatusListItem) : array
    {
        return [
            'id'   => $paymentStatusListItem->id,
            'name' => $paymentStatusListItem->name,
            'code' => $paymentStatusListItem->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_statuses';
    }
}
