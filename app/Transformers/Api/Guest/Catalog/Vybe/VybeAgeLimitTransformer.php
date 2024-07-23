<?php

namespace App\Transformers\Api\Guest\Catalog\Vybe;

use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeAgeLimitTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\Vybe
 */
class VybeAgeLimitTransformer extends BaseTransformer
{
    /**
     * @param VybeAgeLimitListItem $vybeAgeLimitListItem
     *
     * @return array
     */
    public function transform(VybeAgeLimitListItem $vybeAgeLimitListItem) : array
    {
        return [
            'id'   => $vybeAgeLimitListItem->id,
            'code' => $vybeAgeLimitListItem->code,
            'name' => $vybeAgeLimitListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_age_limit';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_age_limits';
    }
}
