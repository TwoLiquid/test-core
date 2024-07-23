<?php

namespace App\Transformers\Api\Admin\General\Admin;

use App\Lists\Admin\Status\AdminStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AdminStatusTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin
 */
class AdminStatusTransformer extends BaseTransformer
{
    /**
     * @param AdminStatusListItem $adminStatusListItem
     *
     * @return array
     */
    public function transform(AdminStatusListItem $adminStatusListItem) : array
    {
        return [
            'id'   => $adminStatusListItem->id,
            'name' => $adminStatusListItem->name,
            'code' => $adminStatusListItem->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'statuses';
    }
}
