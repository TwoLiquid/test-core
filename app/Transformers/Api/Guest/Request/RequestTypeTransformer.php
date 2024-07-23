<?php

namespace App\Transformers\Api\Guest\Request;

use App\Lists\Request\Type\RequestTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class RequestTypeTransformer
 *
 * @package App\Transformers\Api\Guest\Request
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
            'id'   => $requestTypeListItem->id,
            'code' => $requestTypeListItem->code,
            'name' => $requestTypeListItem->name
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
