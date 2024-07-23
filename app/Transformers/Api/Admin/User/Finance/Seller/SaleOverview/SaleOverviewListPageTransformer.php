<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\SaleOverview;

use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class SaleOverviewListPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\SaleOverview
 */
class SaleOverviewListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $sales;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeTypes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemPaymentStatuses;

    /**
     * SaleOverviewListPageTransformer constructor
     *
     * @param EloquentCollection $sales
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $orderItemPaymentStatuses
     */
    public function __construct(
        EloquentCollection $sales,
        EloquentCollection $vybeTypes,
        EloquentCollection $orderItemPaymentStatuses
    )
    {
        $this->sales = $sales;
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
        'sales'
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
        return $this->item([], new SaleOverviewListPageFilterTransformer(
            $this->vybeTypes,
            $this->orderItemPaymentStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeSales() : ?Collection
    {
        return $this->collection($this->sales, new SaleOverviewListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'sale_overview_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'sale_overview_lists';
    }
}
