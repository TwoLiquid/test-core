<?php

namespace App\Transformers\Api\Guest\Vybe\Sort;

use App\Lists\Vybe\Sort\VybeSortListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeSortTransformer
 *
 * @package App\Transformers\Api\Guest\Vybe\Sort
 */
class VybeSortTransformer extends BaseTransformer
{
    /**
     * @param VybeSortListItem $vybeSortListItem
     *
     * @return array
     */
    public function transform(VybeSortListItem $vybeSortListItem) : array
    {
        return [
            'id'   => $vybeSortListItem->id,
            'code' => $vybeSortListItem->code,
            'name' => $vybeSortListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_sort';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_sorts';
    }
}
