<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\Invoice;

use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemPaymentStatusTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\Invoice
 */
class OrderItemPaymentStatusTransformer extends BaseTransformer
{
    /**
     * @param OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
     *
     * @return array
     */
    public function transform(OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem) : array
    {
        return [
            'id'    => $orderItemPaymentStatusListItem->id,
            'code'  => $orderItemPaymentStatusListItem->code,
            'name'  => $orderItemPaymentStatusListItem->name,
            'count' => $orderItemPaymentStatusListItem->count
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_payment_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_payment_statuses';
    }
}
