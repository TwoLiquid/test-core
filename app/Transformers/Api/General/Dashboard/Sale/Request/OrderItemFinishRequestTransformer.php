<?php

namespace App\Transformers\Api\General\Dashboard\Sale\Request;

use App\Models\MongoDb\Order\Request\OrderItemFinishRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemFinishRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Sale\Request
 */
class OrderItemFinishRequestTransformer extends BaseTransformer
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
                null
        ];
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
    public function includeStatus(OrderItemFinishRequest $orderItemFinishRequest) : ?Item
    {
        $requestStatus = $orderItemFinishRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new OrderItemRequestStatusTransformer) : null;
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
