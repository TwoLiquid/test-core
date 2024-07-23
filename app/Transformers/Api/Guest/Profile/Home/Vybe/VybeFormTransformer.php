<?php

namespace App\Transformers\Api\Guest\Profile\Home\Vybe;

use App\Lists\Vybe\Sort\VybeSortList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class VybeFormTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home\Vybe
 */
class VybeFormTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'types',
        'sorts'
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
    public function includeTypes() : ?Collection
    {
        $types = VybeTypeList::getItems();

        return $this->collection($types, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeSorts() : ?Collection
    {
        $sorts = VybeSortList::getItems();

        return $this->collection($sorts, new VybeSortTransformer);
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
