<?php

namespace App\Transformers\Api\General\Auth\Vybe;

use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybePeriodTransformer
 *
 * @package App\Transformers\Api\General\Auth\Vybe
 */
class VybePeriodTransformer extends BaseTransformer
{
    /**
     * @param VybePeriodListItem $vybePeriodListItem
     *
     * @return array
     */
    public function transform(VybePeriodListItem $vybePeriodListItem) : array
    {
        return [
            'id'   => $vybePeriodListItem->id,
            'code' => $vybePeriodListItem->code,
            'name' => $vybePeriodListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_period';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_periods';
    }
}
