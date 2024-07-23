<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Request;

use App\Models\MySql\User\UserBalance;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserBalanceTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Request
 */
class UserBalanceTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user_balance_type'
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

    public function includeUserBalanceType(UserBalance $userBalance) : ?Item
    {
        $userBalanceType = $userBalance->getType();

        return $userBalanceType ? $this->item($userBalanceType, new UserBalanceTypeTransformer) : null;
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
