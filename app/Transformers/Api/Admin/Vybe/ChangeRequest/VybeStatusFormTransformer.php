<?php

namespace App\Transformers\Api\Admin\Vybe\ChangeRequest;

use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class VybeStatusFormTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\ChangeRequest
 */
class VybeStatusFormTransformer extends BaseTransformer
{
    /**
     * @var VybeStatusListItem
     */
    protected VybeStatusListItem $vybeStatusListItem;

    /**
     * VybeStatusFormTransformer constructor
     *
     * @param VybeStatusListItem $vybeStatusListItem
     */
    public function __construct(
        VybeStatusListItem $vybeStatusListItem
    )
    {
        /** @var VybeStatusListItem vybeStatusListItem */
        $this->vybeStatusListItem = $vybeStatusListItem;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'statuses'
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
    public function includeStatuses() : ?Collection
    {
        $statuses = VybeStatusList::getAdminItemsForStatus(
            $this->vybeStatusListItem
        );

        return $this->collection($statuses, new VybeStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
