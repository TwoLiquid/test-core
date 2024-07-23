<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class OrderSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class OrderSectionTransformer extends BaseTransformer
{
    /**
     * @param array $orders
     *
     * @return array
     */
    public function transform(array $orders) : array
    {
        return $orders;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'orders';
    }
}
