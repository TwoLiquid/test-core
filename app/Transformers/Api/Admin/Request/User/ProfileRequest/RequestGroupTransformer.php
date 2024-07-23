<?php

namespace App\Transformers\Api\Admin\Request\User\ProfileRequest;

use App\Lists\Request\Group\RequestGroupListItem;
use App\Transformers\BaseTransformer;

/**
 * Class RequestGroupTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\ProfileRequest
 */
class RequestGroupTransformer extends BaseTransformer
{
    /**
     * @param RequestGroupListItem $requestGroupListItem
     *
     * @return array
     */
    public function transform(RequestGroupListItem $requestGroupListItem) : array
    {
        return [
            'id'    => $requestGroupListItem->id,
            'code'  => $requestGroupListItem->code,
            'name'  => $requestGroupListItem->name,
            'count' => $requestGroupListItem->count
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'request_group';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'request_groups';
    }
}
