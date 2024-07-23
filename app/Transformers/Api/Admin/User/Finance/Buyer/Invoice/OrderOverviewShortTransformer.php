<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\Invoice;

use App\Models\MySql\Order\Order;
use App\Transformers\BaseTransformer;

/**
 * Class OrderOverviewShortTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\Invoice
 */
class OrderOverviewShortTransformer extends BaseTransformer
{
    /**
     * @param Order $order
     *
     * @return array
     */
    public function transform(Order $order) : array
    {
        return [
            'id'         => $order->id,
            'full_id'    => $order->full_id,
            'created_at' => $order->created_at ? $order->created_at->format('Y-m-d\TH:i:s.v\Z') : null
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_overview';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_overviews';
    }
}
