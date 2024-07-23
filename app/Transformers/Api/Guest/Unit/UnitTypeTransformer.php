<?php

namespace App\Transformers\Api\Guest\Unit;

use App\Lists\Unit\Type\UnitTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class UnitTypeTransformer
 *
 * @package App\Transformers\Api\Guest\Unit
 */
class UnitTypeTransformer extends BaseTransformer
{
    /**
     * @param UnitTypeListItem $unitTypeListItem
     *
     * @return array
     */
    public function transform(UnitTypeListItem $unitTypeListItem) : array
    {
        return [
            'id'   => $unitTypeListItem->id,
            'name' => $unitTypeListItem->name,
            'code' => $unitTypeListItem->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'unit_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'unit_types';
    }
}
