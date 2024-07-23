<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class UserBalanceTypeTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class UserBalanceTypeTransformer extends BaseTransformer
{
    /**
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     *
     * @return array
     */
    public function transform(UserBalanceTypeListItem $userBalanceTypeListItem) : array
    {
        return [
            'id'   => $userBalanceTypeListItem->id,
            'code' => $userBalanceTypeListItem->code,
            'name' => $userBalanceTypeListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_balance_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_balance_types';
    }
}