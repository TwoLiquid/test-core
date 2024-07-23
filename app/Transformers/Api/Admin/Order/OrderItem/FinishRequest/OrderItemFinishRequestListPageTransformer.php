<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\FinishRequest;

use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemFinishRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\FinishRequest
 */
class OrderItemFinishRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemFinishRequests;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemStatuses;

    /**
     * OrderItemFinishRequestListPageTransformer constructor
     *
     * @param EloquentCollection $orderItemFinishRequests
     * @param EloquentCollection $statuses
     * @param EloquentCollection $orderItemStatuses
     */
    public function __construct(
        EloquentCollection $orderItemFinishRequests,
        EloquentCollection $statuses,
        EloquentCollection $orderItemStatuses
    )
    {
        $this->orderItemFinishRequests = $orderItemFinishRequests;
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
        'order_item_finish_requests'
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
        return $this->item([], new OrderItemFinishRequestListPageFilterTransformer(
            $this->statuses,
            $this->orderItemStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemFinishRequests() : ?Collection
    {
        return $this->collection($this->orderItemFinishRequests, new OrderItemFinishRequestListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_finish_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_finish_request_lists';
    }
}
