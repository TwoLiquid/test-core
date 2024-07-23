<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal\Pdf;

use App\Lists\Request\Status\RequestStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class RequestStatusTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal\Pdf
 */
class RequestStatusTransformer extends BaseTransformer
{
    /**
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return array
     */
    public function transform(RequestStatusListItem $requestStatusListItem) : array
    {
        return [
            'id'   => $requestStatusListItem->id,
            'code' => $requestStatusListItem->code,
            'name' => $requestStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'request_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'request_statuses';
    }
}
