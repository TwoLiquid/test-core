<?php

namespace App\Transformers\Api\Admin\Invoice\Buyer;

use App\Models\MySql\Order\Order;
use App\Transformers\BaseTransformer;

/**
 * Class OrderOverviewShortTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Buyer
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
            'id'           => $order->id,
            'full_id'      => $order->full_id,
            'subtotal'     => $order->amount,
            'amount_tax'   => $order->amount_tax,
            'amount_total' => $order->amount_total,
            'payment_fee'  => $order->payment_fee,
            'created_at'   => $order->created_at ?
                $order->created_at->format('d.m.Y') :
                null
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
