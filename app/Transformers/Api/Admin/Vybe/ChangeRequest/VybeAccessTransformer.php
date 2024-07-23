<?php

namespace App\Transformers\Api\Admin\Vybe\ChangeRequest;

use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeAccessTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\ChangeRequest
 */
class VybeAccessTransformer extends BaseTransformer
{
    /**
     * @param VybeAccessListItem $vybeAccessListItem
     *
     * @return array
     */
    public function transform(VybeAccessListItem $vybeAccessListItem) : array
    {
        return [
            'id'   => $vybeAccessListItem->id,
            'code' => $vybeAccessListItem->code,
            'name' => $vybeAccessListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_access';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_accesses';
    }
}
