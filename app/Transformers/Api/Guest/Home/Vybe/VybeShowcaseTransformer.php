<?php

namespace App\Transformers\Api\Guest\Home\Vybe;

use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeShowcaseTransformer
 *
 * @package App\Transformers\Api\Guest\Home\Vybe
 */
class VybeShowcaseTransformer extends BaseTransformer
{
    /**
     * @param VybeShowcaseListItem $vybeShowcaseListItem
     *
     * @return array
     */
    public function transform(VybeShowcaseListItem $vybeShowcaseListItem) : array
    {
        return [
            'id'   => $vybeShowcaseListItem->id,
            'code' => $vybeShowcaseListItem->code,
            'name' => $vybeShowcaseListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_showcase';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_showcases';
    }
}
