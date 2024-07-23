<?php

namespace App\Transformers\Api\Guest\Admin\Status;

use App\Lists\Admin\Status\AdminStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AdminStatusTransformer
 *
 * @package App\Transformers\Api\Guest\Admin\Status
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
            'code' => $adminStatusListItem->code,
            'name' => $adminStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'admin_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'admin_statuses';
    }
}
