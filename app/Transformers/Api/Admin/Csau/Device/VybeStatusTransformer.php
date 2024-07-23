<?php

namespace App\Transformers\Api\Admin\Csau\Device;

use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeStatusTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Device
 */
class VybeStatusTransformer extends BaseTransformer
{
    /**
     * @param VybeStatusListItem $vybeStatusListItem
     *
     * @return array
     */
    public function transform(VybeStatusListItem $vybeStatusListItem) : array
    {
        return [
            'id'   => $vybeStatusListItem->id,
            'code' => $vybeStatusListItem->code,
            'name' => $vybeStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_statuses';
    }
}
