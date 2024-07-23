<?php

namespace App\Transformers\Api\Admin\Order;

use App\Transformers\Api\Admin\Order\OrderItem\OrderItemPaymentStatusTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class SaleOverviewListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class SaleOverviewListPageFilterTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeTypes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemPaymentStatuses;

    /**
     * SaleOverviewListPageFilterTransformer constructor
     *
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $orderItemPaymentStatuses
     */
    public function __construct(
        EloquentCollection $vybeTypes,
        EloquentCollection $orderItemPaymentStatuses
    )
    {
        $this->vybeTypes = $vybeTypes;
        $this->orderItemPaymentStatuses = $orderItemPaymentStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'order_item_payment_statuses'
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
    public function includeVybeTypes() : ?Collection
    {
        $vybeTypes = $this->vybeTypes;

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemPaymentStatuses() : ?Collection
    {
        $orderItemPaymentStatuses = $this->orderItemPaymentStatuses;

        return $this->collection($orderItemPaymentStatuses, new OrderItemPaymentStatusTransformer);
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
