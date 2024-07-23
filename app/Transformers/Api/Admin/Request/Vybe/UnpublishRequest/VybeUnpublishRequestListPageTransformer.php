<?php

namespace App\Transformers\Api\Admin\Request\Vybe\UnpublishRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnpublishRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\UnpublishRequest
 */
class VybeUnpublishRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeUnpublishRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * VybeUnpublishRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $vybeUnpublishRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $vybeUnpublishRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection vybeUnpublishRequests */
        $this->vybeUnpublishRequests = $vybeUnpublishRequests;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item
     */
    public function includeForm() : Item
    {
        return $this->item([], new FormTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new VybeUnpublishRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->vybeUnpublishRequests, new VybeUnpublishRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_unpublish_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_unpublish_request_lists';
    }
}
