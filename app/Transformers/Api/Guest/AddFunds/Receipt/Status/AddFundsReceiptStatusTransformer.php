<?php

namespace App\Transformers\Api\Guest\AddFunds\Receipt\Status;

use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AddFundsReceiptStatusTransformer
 *
 * @package App\Transformers\Api\Guest\AddFunds\Receipt\Status
 */
class AddFundsReceiptStatusTransformer extends BaseTransformer
{
    /**
     * @param AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
     *
     * @return array
     */
    public function transform(AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem) : array
    {
        return [
            'id'   => $addFundsReceiptStatusListItem->id,
            'code' => $addFundsReceiptStatusListItem->code,
            'name' => $addFundsReceiptStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'add_funds_receipt_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'add_funds_receipt_statuses';
    }
}
