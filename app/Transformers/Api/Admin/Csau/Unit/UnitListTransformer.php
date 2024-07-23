<?php

namespace App\Transformers\Api\Admin\Csau\Unit;

use App\Models\MySql\Unit;
use App\Transformers\BaseTransformer;

/**
 * Class UnitListTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Unit
 */
class UnitListTransformer extends BaseTransformer
{
    /**
     * @param Unit $unit
     *
     * @return array
     */
    public function transform(Unit $unit) : array
    {
        return [
            'id'          => $unit->id,
            'name'        => $unit->getTranslations('name'),
            'code'        => $unit->code,
            'visible'     => $unit->visible,
            'vybes_count' => $unit->vybes_count,
            'duration'    => $unit->duration
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
