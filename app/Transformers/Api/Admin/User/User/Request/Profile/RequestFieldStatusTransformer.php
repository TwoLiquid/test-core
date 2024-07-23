<?php

namespace App\Transformers\Api\Admin\User\User\Request\Profile;

use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class RequestFieldStatusTransformer
 *
 * @package App\Transformers\Api\Admin\User\User\Request\Profile
 */
class RequestFieldStatusTransformer extends BaseTransformer
{
    /**
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return array
     */
    public function transform(RequestFieldStatusListItem $requestFieldStatusListItem) : array
    {
        return [
            'id'   => $requestFieldStatusListItem->id,
            'code' => $requestFieldStatusListItem->code,
            'name' => $requestFieldStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'request_field_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'request_field_statuses';
    }
}
