<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\FinishRequest;

use App\Models\MongoDb\Order\Request\OrderItemFinishRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemFinishRequestListTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\FinishRequest
 */
class OrderItemFinishRequestListTransformer extends BaseTransformer
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
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return array
     */
    public function transform(OrderItemFinishRequest $orderItemFinishRequest) : array
    {
        return [
            'id'                    => $orderItemFinishRequest->_id,
            'from_request_datetime' => $orderItemFinishRequest->from_request_datetime ?
                $orderItemFinishRequest->from_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'to_request_datetime'   => $orderItemFinishRequest->to_request_datetime ?
                $orderItemFinishRequest->to_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'waiting'               => $orderItemFinishRequest->waiting,
            'order_date'            => $orderItemFinishRequest->item->order ?
                $orderItemFinishRequest->item->order->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeItem(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $orderItem = null;

        if ($orderItemFinishRequest->relationLoaded('item')) {
            $orderItem = $orderItemFinishRequest->item;
        }

        return $orderItem ? $this->item($orderItem, new OrderItemShortTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeVybe(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $vybe = null;

        if ($orderItemFinishRequest->relationLoaded('item')) {
            $orderItem = $orderItemFinishRequest->item;

            if ($orderItem->relationLoaded('vybe')) {
                $vybe = $orderItem->vybe;
            }
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeBuyer(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $buyer = null;

        if ($orderItemFinishRequest->relationLoaded('buyer')) {
            $buyer = $orderItemFinishRequest->buyer;
        }

        return $buyer ? $this->item($buyer, new UserTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeSeller(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $seller = null;

        if ($orderItemFinishRequest->relationLoaded('seller')) {
            $seller = $orderItemFinishRequest->seller;
        }

        return $seller ? $this->item($seller, new UserTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeInitiator(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $initiator = $orderItemFinishRequest->getInitiator();

        return $initiator ? $this->item($initiator, new OrderItemRequestInitiatorTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeFromOrderItemStatus(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $fromOrderItemStatus = $orderItemFinishRequest->getFromOrderItemStatus();

        return $fromOrderItemStatus ? $this->item($fromOrderItemStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeToOrderItemStatus(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $toOrderItemStatus = $orderItemFinishRequest->getToOrderItemStatus();

        return $toOrderItemStatus ? $this->item($toOrderItemStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeAction(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $action = $orderItemFinishRequest->getAction();

        return $action ? $this->item($action, new OrderItemRequestActionTransformer) : null;
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return Item|null
     */
    public function includeStatus(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $status = $orderItemFinishRequest->getRequestStatus();

        return $status ? $this->item($status, new OrderItemRequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_finish_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_finish_requests';
    }
}
