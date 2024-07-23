<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\Invoice;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceSellerListTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\Invoice
 */
class InvoiceSellerListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'order_items'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @param array $seller
     *
     * @return Item|null
     */
    public function includeUser(array $seller) : ?Item
    {
        $user = null;

        if ($seller['user']) {
            $user = $seller['user'];
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param array $seller
     *
     * @return Collection|null
     */
    public function includeOrderItems(array $seller) : ?Collection
    {
        $orderItems = null;

        if ($seller['items']) {
            $orderItems = $seller['items'];
        }

        return $orderItems ? $this->collection($orderItems, new OrderItemShortTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'seller_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'seller_lists';
    }
}
