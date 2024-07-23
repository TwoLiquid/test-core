<?php

namespace App\Transformers\Api\Admin\Vybe\VybeList;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\VybeList
 */
class VybeListPageFilterTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeStatuses;

    /**
     * VybeListPageFilterTransformer constructor
     *
     * @param EloquentCollection $vybeStatuses
     */
    public function __construct(
        EloquentCollection $vybeStatuses
    )
    {
        $this->vybeStatuses = $vybeStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_statuses'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeVybeStatuses() : ?Collection
    {
        $vybeStatuses = $this->vybeStatuses;

        return $this->collection($vybeStatuses, new VybeStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'filters';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'filters';
    }
}
