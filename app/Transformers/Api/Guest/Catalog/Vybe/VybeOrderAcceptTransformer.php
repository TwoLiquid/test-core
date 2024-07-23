<?php

namespace App\Transformers\Api\Guest\Catalog\Vybe;

use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeOrderAccessTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\Vybe
 */
class VybeOrderAcceptTransformer extends BaseTransformer
{
    /**
     * @param VybeOrderAcceptListItem $vybeOrderAcceptListItem
     *
     * @return array
     */
    public function transform(VybeOrderAcceptListItem $vybeOrderAcceptListItem) : array
    {
        return [
            'id'   => $vybeOrderAcceptListItem->id,
            'code' => $vybeOrderAcceptListItem->code,
            'name' => $vybeOrderAcceptListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_order_access';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_order_accesses';
    }
}
