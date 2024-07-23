<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt;

use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AddFundsReceiptStatusTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt
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
            'id'    => $addFundsReceiptStatusListItem->id,
            'name'  => $addFundsReceiptStatusListItem->name,
            'code'  => $addFundsReceiptStatusListItem->code,
            'count' => $addFundsReceiptStatusListItem->count
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
