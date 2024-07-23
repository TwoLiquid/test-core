<?php

namespace App\Transformers\Api\General\Dashboard\Vybe\Form;

use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FavoriteVybeFormTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe\Form
 */
class FavoriteVybeFormTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'types'
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
