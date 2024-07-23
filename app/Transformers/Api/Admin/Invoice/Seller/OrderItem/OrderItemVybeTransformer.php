<?php

namespace App\Transformers\Api\Admin\Invoice\Seller\OrderItem;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemVybeTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller\OrderItem
 */
class OrderItemVybeTransformer extends BaseTransformer
{
    /**
     * @param OrderItem $orderItem
     *
     * @return array
     */
    public function transform(OrderItem $orderItem) : array
    {
        /**
         * Getting order item vybe
         */
        $vybe = $orderItem->appearanceCase->vybe;

        return [
            'id'           => $vybe->id,
            'title'        => $vybe->title,
            'vybe_version' => $orderItem->vybe_version
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
