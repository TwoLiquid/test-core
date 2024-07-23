<?php

namespace App\Transformers\Api\General\Dashboard\Wallet\AddFunds;

use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AddFundsReceiptStatusTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet\AddFunds
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
            'name' => $addFundsReceiptStatusListItem->name,
            'code' => $addFundsReceiptStatusListItem->code
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
