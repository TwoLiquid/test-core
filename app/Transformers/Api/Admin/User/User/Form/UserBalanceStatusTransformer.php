<?php

namespace App\Transformers\Api\Admin\User\User\Form;

use App\Lists\User\Balance\Status\UserBalanceStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class UserBalanceStatusTransformer
 *
 * @package App\Transformers\Api\Admin\User\User\Form
 */
class UserBalanceStatusTransformer extends BaseTransformer
{
    /**
     * @param UserBalanceStatusListItem $userBalanceStatusListItem
     *
     * @return array
     */
    public function transform(UserBalanceStatusListItem $userBalanceStatusListItem) : array
    {
        return [
            'id'   => $userBalanceStatusListItem->id,
            'code' => $userBalanceStatusListItem->code,
            'name' => $userBalanceStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_balance_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_balance_statuses';
    }
}
