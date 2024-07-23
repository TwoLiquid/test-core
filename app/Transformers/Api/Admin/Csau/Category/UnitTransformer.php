<?php

namespace App\Transformers\Api\Admin\Csau\Category;

use App\Models\MySql\Unit;
use App\Transformers\BaseTransformer;

/**
 * Class UnitTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Category
 */
class UnitTransformer extends BaseTransformer
{
    /**
     * @param Unit $unit
     *
     * @return array
     */
    public function transform(Unit $unit) : array
    {
        return [
            'id'       => $unit->id,
            'code'     => $unit->code,
            'name'     => $unit->getTranslations('name'),
            'duration' => $unit->duration,
            'visible'  => $unit->visible
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
