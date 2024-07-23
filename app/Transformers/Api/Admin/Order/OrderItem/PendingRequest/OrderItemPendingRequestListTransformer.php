<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\PendingRequest;

use App\Models\MongoDb\Order\Request\OrderItemPendingRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemPendingRequestListTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\PendingRequest
 */
class OrderItemPendingRequestListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'item',
        'vybe',
        'buyer',
        'seller',
        'initiator',
        'from_order_item_status',
        'to_order_item_status',
        'action',
        'status'
    ];

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return array
     */
    public function transform(OrderItemPendingRequest $orderItemPendingRequest) : array
    {
        return [
            'id'                    => $orderItemPendingRequest->_id,
            'from_request_datetime' => $orderItemPendingRequest->from_request_datetime ?
                $orderItemPendingRequest->from_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'to_request_datetime'   => $orderItemPendingRequest->to_request_datetime ?
                $orderItemPendingRequest->to_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'waiting'               => $orderItemPendingRequest->waiting,
            'order_date'            => $orderItemPendingRequest->item->order ?
                $orderItemPendingRequest->item->order->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeItem(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $orderItem = null;

        if ($orderItemPendingRequest->relationLoaded('item')) {
            $orderItem = $orderItemPendingRequest->item;
        }

        return $orderItem ? $this->item($orderItem, new OrderItemShortTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeVybe(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $vybe = null;

        if ($orderItemPendingRequest->relationLoaded('item')) {
            $orderItem = $orderItemPendingRequest->item;

            if ($orderItem->relationLoaded('vybe')) {
                $vybe = $orderItem->vybe;
            }
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeBuyer(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $buyer = null;

        if ($orderItemPendingRequest->relationLoaded('buyer')) {
            $buyer = $orderItemPendingRequest->buyer;
        }

        return $buyer ? $this->item($buyer, new UserTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeSeller(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $seller = null;

        if ($orderItemPendingRequest->relationLoaded('seller')) {
            $seller = $orderItemPendingRequest->seller;
        }

        return $seller ? $this->item($seller, new UserTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeInitiator(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $initiator = $orderItemPendingRequest->getInitiator();

        return $initiator ? $this->item($initiator, new OrderItemRequestInitiatorTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeFromOrderItemStatus(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $fromOrderItemStatus = $orderItemPendingRequest->getFromOrderItemStatus();

        return $fromOrderItemStatus ? $this->item($fromOrderItemStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeToOrderItemStatus(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $toOrderItemStatus = $orderItemPendingRequest->getToOrderItemStatus();

        return $toOrderItemStatus ? $this->item($toOrderItemStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeAction(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $action = $orderItemPendingRequest->getAction();

        return $action ? $this->item($action, new OrderItemRequestActionTransformer) : null;
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return Item|null
     */
    public function includeStatus(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $status = $orderItemPendingRequest->getRequestStatus();

        return $status ? $this->item($status, new OrderItemRequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_pending_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_pending_requests';
    }
}
