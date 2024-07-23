<?php

namespace App\Transformers\Api\General\Dashboard\Vybe;

use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeOrderAcceptTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe
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
        return 'vybe_order_accept';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_order_accepts';
    }
}
