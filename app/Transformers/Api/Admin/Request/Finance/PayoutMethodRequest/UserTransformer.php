<?php

namespace App\Transformers\Api\Admin\Request\Finance\PayoutMethodRequest;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\PayoutMethodRequest
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'balances'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'       => $user->id,
            'auth_id'  => $user->auth_id,
            'username' => $user->username
        ];
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeBalances(User $user) : ?Collection
    {
        $balances = $user->balances;

        return $balances ? $this->collection($balances, new UserBalanceTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'users';
    }
}
