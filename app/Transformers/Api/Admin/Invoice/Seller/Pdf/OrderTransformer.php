<?php

namespace App\Transformers\Api\Admin\Invoice\Seller\Pdf;

use App\Models\MySql\Order\Order;
use App\Transformers\BaseTransformer;

/**
 * Class OrderTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller\Pdf
 */
class OrderTransformer extends BaseTransformer
{
    /**
     * @param Order $order
     *
     * @return array
     */
    public function transform(Order $order) : array
    {
        return [
            'id'      => $order->id,
            'paid_at' => $order->paid_at
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'orders';
    }
}
