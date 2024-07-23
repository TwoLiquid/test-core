<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Models\MySql\User\User;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt\Pdf\BillingTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'seller_balance',
        'payout_methods',
        'billing'
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
    public function includePayoutMethods(User $user) : ?Collection
    {
        $payoutMethods = $user->payoutMethods;

        return $payoutMethods ? $this->collection($payoutMethods, new PaymentMethodTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeSellerBalance(User $user) : ?Item
    {
        $sellerBalance = $user->getSellerBalance();

        return $sellerBalance ? $this->item($sellerBalance, new UserBalanceTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeBilling(User $user) : ?Item
    {
        $billing = null;

        if ($user->relationLoaded('billing')) {
            $billing = $user->billing;
        }

        return $billing ? $this->item($billing, new BillingTransformer) : null;
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
