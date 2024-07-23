<?php

namespace App\Transformers\Api\General\Dashboard\Sale\Request;

use App\Models\MongoDb\Order\Request\OrderItemPendingRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemPendingRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Sale\Request
 */
class OrderItemPendingRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'from_order_item_status',
        'to_order_item_status',
        'action',
        'initiator',
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
                null
        ];
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
    public function includeStatus(OrderItemPendingRequest $orderItemPendingRequest) : ?Item
    {
        $requestStatus = $orderItemPendingRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new OrderItemRequestStatusTransformer) : null;
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
