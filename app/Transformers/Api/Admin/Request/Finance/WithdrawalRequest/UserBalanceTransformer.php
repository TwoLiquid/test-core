<?php

namespace App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest;

use App\Models\MySql\User\UserBalance;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserBalanceTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest
 */
class UserBalanceTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user_balance_type',
        'user_balance_status'
    ];

    /**
     * @param UserBalance $userBalance
     *
     * @return array
     */
    public function transform(UserBalance $userBalance) : array
    {
        return [
            'id'     => $userBalance->id,
            'amount' => $userBalance->amount
        ];
    }

    /**
     * @param UserBalance $userBalance
     *
     * @return Item|null
     */
    public function includeUserBalanceType(UserBalance $userBalance) : ?Item
    {
        $userBalanceType = $userBalance->getType();

        return $userBalanceType ? $this->item($userBalanceType, new UserBalanceTypeTransformer) : null;
    }

    /**
     * @param UserBalance $userBalance
     *
     * @return Item|null
     */
    public function includeUserBalanceStatus(UserBalance $userBalance) : ?Item
    {
        $userBalanceStatus = $userBalance->getStatus();

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