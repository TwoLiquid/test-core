<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\RescheduleRequest;

use App\Models\MongoDb\Order\Request\OrderItemRescheduleRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemRescheduleRequestListTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\RescheduleRequest
 */
class OrderItemRescheduleRequestListTransformer extends BaseTransformer
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
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return array
     */
    public function transform(OrderItemRescheduleRequest $orderItemRescheduleRequest) : array
    {
        return [
            'id'                    => $orderItemRescheduleRequest->_id,
            'from_request_datetime' => $orderItemRescheduleRequest->from_request_datetime ?
                $orderItemRescheduleRequest->from_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'to_request_datetime'   => $orderItemRescheduleRequest->to_request_datetime ?
                $orderItemRescheduleRequest->to_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'waiting'               => $orderItemRescheduleRequest->waiting,
            'order_date'            => $orderItemRescheduleRequest->item->order ?
                $orderItemRescheduleRequest->item->order->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeItem(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $orderItem = null;

        if ($orderItemRescheduleRequest->relationLoaded('item')) {
            $orderItem = $orderItemRescheduleRequest->item;
        }

        return $orderItem ? $this->item($orderItem, new OrderItemShortTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeVybe(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $vybe = null;

        if ($orderItemRescheduleRequest->relationLoaded('item')) {
            $orderItem = $orderItemRescheduleRequest->item;

            if ($orderItem->relationLoaded('vybe')) {
                $vybe = $orderItem->vybe;
            }
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeBuyer(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $buyer = null;

        if ($orderItemRescheduleRequest->relationLoaded('buyer')) {
            $buyer = $orderItemRescheduleRequest->buyer;
        }

        return $buyer ? $this->item($buyer, new UserTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeSeller(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $seller = null;

        if ($orderItemRescheduleRequest->relationLoaded('seller')) {
            $seller = $orderItemRescheduleRequest->seller;
        }

        return $seller ? $this->item($seller, new UserTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeInitiator(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $initiator = $orderItemRescheduleRequest->getInitiator();

        return $initiator ? $this->item($initiator, new OrderItemRequestInitiatorTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeFromOrderItemStatus(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $fromOrderItemStatus = $orderItemRescheduleRequest->getFromOrderItemStatus();

        return $fromOrderItemStatus ? $this->item($fromOrderItemStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeToOrderItemStatus(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $toOrderItemStatus = $orderItemRescheduleRequest->getToOrderItemStatus();

        return $toOrderItemStatus ? $this->item($toOrderItemStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeAction(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $action = $orderItemRescheduleRequest->getAction();

        return $action ? $this->item($action, new OrderItemRequestActionTransformer) : null;
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return Item|null
     */
    public function includeStatus(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $status = $orderItemRescheduleRequest->getRequestStatus();

        return $status ? $this->item($status, new OrderItemRequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_reschedule_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_reschedule_requests';
    }
}
