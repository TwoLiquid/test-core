<?php

namespace App\Transformers\Api\Admin\Csau\Unit;

use App\Models\MySql\Unit;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UnitWithPaginationTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Unit
 */
class UnitWithPaginationTransformer extends BaseTransformer
{
    /**
     * @var int
     */
    protected int $page;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybes'
    ];

    /**
     * UnitWithPaginationTransformer constructor
     *
     * @param int $page
     * @param int $perPage
     */
    public function __construct(
        int $page = 1,
        int $perPage = 18
    )
    {
        /** @var int page */
        $this->page = $page;

        /** @var int perPage */
        $this->perPage = $perPage;
    }

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
            'vybes_count' => $unit->vybes_count
        ];
    }

    /**
     * @param Unit $unit
     *
     * @return Item|null
     */
    public function includeVybes(Unit $unit) : ?Item
    {
        $vybes = null;

        if ($unit->relationLoaded('vybes')) {
            $vybes = $unit->vybes;
        }

        return $vybes ?
            $this->item(
                $vybes,
                new PaginatedVybeTransformer(
                    $this->page,
                    $this->perPage
                )
            ) : null;
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
