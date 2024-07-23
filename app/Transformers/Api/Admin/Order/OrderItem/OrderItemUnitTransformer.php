<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Models\MySql\Unit;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemUnitTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
 */
class OrderItemUnitTransformer extends BaseTransformer
{
    /**
     * @param Unit $unit
     *
     * @return array
     */
    public function transform(Unit $unit) : array
    {
        return [
            'id'   => $unit->id,
            'code' => $unit->code,
            'name' => $unit->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'unit';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'units';
    }
}
