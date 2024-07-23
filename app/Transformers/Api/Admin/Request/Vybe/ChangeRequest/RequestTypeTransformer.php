<?php

namespace App\Transformers\Api\Admin\Request\Vybe\ChangeRequest;

use App\Lists\Request\Type\RequestTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class RequestTypeTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\DeactivationRequest
 */
class RequestTypeTransformer extends BaseTransformer
{
    /**
     * @param RequestTypeListItem $requestTypeListItem
     *
     * @return array
     */
    public function transform(RequestTypeListItem $requestTypeListItem) : array
    {
        return [
            'id'    => $requestTypeListItem->id,
            'code'  => $requestTypeListItem->code,
            'name'  => $requestTypeListItem->name,
            'count' => $requestTypeListItem->count
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'request_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'request_types';
    }
}
