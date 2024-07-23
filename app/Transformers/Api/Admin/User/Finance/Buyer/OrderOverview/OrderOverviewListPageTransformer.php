<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\OrderOverview;

use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class OrderOverviewListPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\OrderOverview
 */
class OrderOverviewListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orders;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeTypes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemPaymentStatuses;

    /**
     * OrderOverviewListPageTransformer constructor
     *
     * @param EloquentCollection $orders
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $orderItemPaymentStatuses
     */
    public function __construct(
        EloquentCollection $orders,
        EloquentCollection $vybeTypes,
        EloquentCollection $orderItemPaymentStatuses
    )
    {
        $this->orders = $orders;
        $this->vybeTypes = $vybeTypes;
        $this->orderItemPaymentStatuses = $orderItemPaymentStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'order_item_payment_statuses',
        'filters',
        'orders'
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
        $vybeTypes = VybeTypeList::getItems();

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemPaymentStatuses() : ?Collection
    {
        $orderItemPaymentStatuses = OrderItemPaymentStatusList::getItems();

        return $this->collection($orderItemPaymentStatuses, new OrderItemPaymentStatusTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new OrderOverviewListPageFilterTransformer(
            $this->vybeTypes,
            $this->orderItemPaymentStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeOrders() : ?Collection
    {
        return $this->collection($this->orders, new OrderOverviewListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_overview_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_overview_lists';
    }
}
