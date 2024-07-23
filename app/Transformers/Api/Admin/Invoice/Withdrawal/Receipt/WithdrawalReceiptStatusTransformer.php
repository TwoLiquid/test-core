<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class WithdrawalReceiptStatusTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class WithdrawalReceiptStatusTransformer extends BaseTransformer
{
    /**
     * @param WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem
     *
     * @return array
     */
    public function transform(WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem) : array
    {
        return [
            'id'    => $withdrawalReceiptStatusListItem->id,
            'code'  => $withdrawalReceiptStatusListItem->code,
            'name'  => $withdrawalReceiptStatusListItem->name,
            'count' => $withdrawalReceiptStatusListItem->count
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_receipt_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_receipt_statuses';
    }
}
