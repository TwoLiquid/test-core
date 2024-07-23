<?php

namespace App\Transformers\Api\Admin\Request\Vybe\DeletionRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeDeletionRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\DeletionRequest
 */
class VybeDeletionRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeDeletionRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * VybeDeletionRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $vybeDeletionRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $vybeDeletionRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection vybeDeletionRequests */
        $this->vybeDeletionRequests = $vybeDeletionRequests;
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
        return $this->item([], new VybeDeletionRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->vybeDeletionRequests, new VybeDeletionRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_deletion_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_deletion_request_lists';
    }
}
