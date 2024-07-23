<?php

namespace App\Transformers\Api\Admin\Vybe\VybeList\AppearanceCase;

use App\Models\MySql\Unit;
use App\Transformers\BaseTransformer;

/**
 * Class UnitTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\VybeList\AppearanceCase
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
            'name'     => $unit->name,
            'code'     => $unit->code,
            'duration' => $unit->duration
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
