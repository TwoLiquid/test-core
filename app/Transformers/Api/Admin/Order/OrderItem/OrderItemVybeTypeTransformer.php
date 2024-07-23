<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeTypeTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
 */
class OrderItemVybeTypeTransformer extends BaseTransformer
{
    /**
     * @param VybeTypeListItem $vybeTypeListItem
     *
     * @return array
     */
    public function transform(VybeTypeListItem $vybeTypeListItem) : array
    {
        return [
            'id'   => $vybeTypeListItem->id,
            'code' => $vybeTypeListItem->code,
            'name' => $vybeTypeListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_types';
    }
}
