<?php

namespace App\Transformers\Api\Guest\Unit;

use App\Models\MySql\Unit;
use App\Transformers\BaseTransformer;

/**
 * Class EventUnitTransformer
 *
 * @package App\Transformers\Api\Guest\Unit
 */
class EventUnitTransformer extends BaseTransformer
{
    /**
     * @param Unit $unit
     *
     * @return array
     */
    public function transform(Unit $unit) : array
    {
        return [
            'id'      => $unit->id,
            'name'    => $unit->name,
            'code'    => $unit->code,
            'visible' => $unit->visible
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
