<?php

namespace App\Transformers\Api\Admin\Csau\Unit;

use App\Models\MySql\Unit;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class UnitTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Unit
 */
class UnitTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybes'
    ];

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
            'duration'    => $unit->duration,
            'visible'     => $unit->visible,
            'vybes_count' => $unit->vybes_count ? $unit->vybes_count : 0
        ];
    }

    /**
     * @param Unit $unit
     *
     * @return Collection|null
     */
    public function includeVybes(Unit $unit) : ?Collection
    {
        $vybes = null;

        if ($unit->relationLoaded('vybes')) {
            $vybes = $unit->vybes;
        }

        return $vybes ? $this->collection($vybes, new VybeTransformer) : null;
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
