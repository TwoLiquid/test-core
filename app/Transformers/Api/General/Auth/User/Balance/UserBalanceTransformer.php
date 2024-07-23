<?php

namespace App\Transformers\Api\General\Auth\User\Balance;

use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MySql\User\UserBalance;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserBalanceTransformer
 *
 * @package App\Transformers\Api\General\Auth\User\Balance
 */
class UserBalanceTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'type',
        'status'
    ];

    /**
     * @param UserBalance $userBalance
     *
     * @return array
     */
    public function transform(UserBalance $userBalance) : array
    {
        return [
            'id'             => $userBalance->id,
            'full_id'        => $userBalance->full_id,
            'amount'         => $userBalance->amount,
            'pending_amount' => $userBalance->pending_amount
        ];
    }

    /**
     * @param UserBalance $userBalance
     *
     * @return Item|null
     */
    public function includeType(UserBalance $userBalance) : ?Item
    {
        $userBalanceType = UserBalanceTypeList::getItem(
            $userBalance->type_id
        );

        return $userBalanceType ? $this->item($userBalanceType, new UserBalanceTypeTransformer) : null;
    }

    /**
     * @param UserBalance $userBalance
     *
     * @return Item|null
     */
    public function includeStatus(UserBalance $userBalance) : ?Item
    {
        $userBalanceStatus = UserBalanceStatusList::getItem(
            $userBalance->status_id
        );

        return $userBalanceStatus ? $this->item($userBalanceStatus, new UserBalanceStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_balance';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_balances';
    }
}
