<?php

namespace App\Transformers\Api\Admin\Vybe\VybeList;

use App\Transformers\Api\Admin\Vybe\Vybe\VybeFilterFormTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class VybeListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\VybeList
 */
class VybeListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeStatuses;

    /**
     * VybeListPageTransformer constructor
     *
     * @param EloquentCollection $vybes
     * @param EloquentCollection $vybeStatuses
     */
    public function __construct(
        EloquentCollection $vybes,
        EloquentCollection $vybeStatuses
    )
    {
        $this->vybes = $vybes;
        $this->vybeStatuses = $vybeStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'vybes'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeForm() : ?Item
    {
        return $this->item([], new VybeFilterFormTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new VybeListPageFilterTransformer(
            $this->vybeStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeVybes() : ?Collection
    {
        return $this->collection($this->vybes, new VybeListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_lists';
    }
}
