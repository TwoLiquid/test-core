<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\PendingRequest;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class OrderItemPendingRequestListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\PendingRequest
 */
class OrderItemPendingRequestListPageFilterTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemStatuses;

    /**
     * OrderItemPendingRequestListPageFilterTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $orderItemStatuses
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $orderItemStatuses
    )
    {
        $this->statuses = $statuses;
        $this->orderItemStatuses = $orderItemStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'statuses',
        'order_item_statuses'
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
        $statuses = $this->statuses;

        return $this->collection($statuses, new OrderItemRequestStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemStatuses() : ?Collection
    {
        $orderItemStatuses = $this->orderItemStatuses;

        return $this->collection($orderItemStatuses, new OrderItemStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'filters';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'filters';
    }
}