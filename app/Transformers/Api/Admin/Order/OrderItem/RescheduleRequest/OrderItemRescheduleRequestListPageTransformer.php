<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\RescheduleRequest;

use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemRescheduleRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\RescheduleRequest
 */
class OrderItemRescheduleRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemRescheduleRequests;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemStatuses;

    /**
     * OrderItemRescheduleRequestListPageTransformer constructor
     *
     * @param EloquentCollection $orderItemRescheduleRequests
     * @param EloquentCollection $statuses
     * @param EloquentCollection $orderItemStatuses
     */
    public function __construct(
        EloquentCollection $orderItemRescheduleRequests,
        EloquentCollection $statuses,
        EloquentCollection $orderItemStatuses
    )
    {
        $this->orderItemRescheduleRequests = $orderItemRescheduleRequests;
        $this->statuses = $statuses;
        $this->orderItemStatuses = $orderItemStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'statuses',
        'order_item_statuses',
        'filters',
        'order_item_reschedule_requests'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeStatuses() : ?Collection
    {
        $statuses = OrderItemRequestStatusList::getItems();

        return $this->collection($statuses, new OrderItemRequestStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemStatuses() : ?Collection
    {
        $orderItemStatuses = OrderItemStatusList::getItems();

        return $this->collection($orderItemStatuses, new OrderItemStatusTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new OrderItemRescheduleRequestListPageFilterTransformer(
            $this->statuses,
            $this->orderItemStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemRescheduleRequests() : ?Collection
    {
        return $this->collection($this->orderItemRescheduleRequests, new OrderItemRescheduleRequestListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_reschedule_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_reschedule_request_lists';
    }
}
