<?php

namespace App\Transformers\Api\General\Dashboard\Wallet;

use App\Models\MySql\User\UserBalance;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class WalletPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet
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
        return $this->item($userBalance->getType(), new UserBalanceTypeTransformer);
    }

    /**
     * @param UserBalance $userBalance
     *
     * @return Item|null
     */
    public function includeStatus(UserBalance $userBalance) : ?Item
    {
        return $this->item($userBalance->getStatus(), new UserBalanceStatusTransformer);
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
