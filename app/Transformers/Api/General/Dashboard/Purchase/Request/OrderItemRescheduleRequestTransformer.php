<?php

namespace App\Transformers\Api\General\Dashboard\Purchase\Request;

use App\Models\MongoDb\Order\Request\OrderItemRescheduleRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemRescheduleRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Purchase\Request
 */
class OrderItemRescheduleRequestTransformer extends BaseTransformer
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
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return array
     */
    public function transform(OrderItemRescheduleRequest $orderItemRescheduleRequest) : array
    {
        return [
            'id'                    => $orderItemRescheduleRequest->_id,
            'datetime_from'         => $orderItemRescheduleRequest->datetime_from->format('Y-m-d\TH:i:s.v\Z'),
            'datetime_to'           => $orderItemRescheduleRequest->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
            'from_request_datetime' => $orderItemRescheduleRequest->from_request_datetime ?
                $orderItemRescheduleRequest->from_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'to_request_datetime'   => $orderItemRescheduleRequest->to_request_datetime ?
                $orderItemRescheduleRequest->to_request_datetime->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
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
    public function includeStatus(OrderItemRescheduleRequest $orderItemRescheduleRequest) : ?Item
    {
        $requestStatus = $orderItemRescheduleRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new OrderItemRequestStatusTransformer) : null;
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
