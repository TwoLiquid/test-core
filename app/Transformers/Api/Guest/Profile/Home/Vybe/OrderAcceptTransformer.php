<?php

namespace App\Transformers\Api\Guest\Profile\Home\Vybe;

use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderAcceptTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home\Vybe
 */
class OrderAcceptTransformer extends BaseTransformer
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
        return 'order_accept';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_accepts';
    }
}
