<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class BuyerListTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class BuyerListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place',
        'account_status',
        'buyer_status'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'               => $user->id,
            'auth_id'          => $user->auth_id,
            'account_id'       => $user->id,
            'buyer_id'         => $user->getBuyerBalance()->full_id,
            'username'         => $user->username,
            'balance'          => $user->getBuyerBalance()->amount,
            'order_items'      => null,
            'total_paid'       => null,
            'pending_requests' => null,
            'disputes'         => null
        ];
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeCountryPlace(User $user) : ?Item
    {
        $countryPlace = null;

        if ($user->relationLoaded('billing')) {
            $billing = $user->billing;

            if ($billing->relationLoaded('countryPlace')) {
                $countryPlace = $billing->countryPlace;
            }
        }

        return $countryPlace ? $this->item($countryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeAccountStatus(User $user) : ?Item
    {
        $accountStatus = $user->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeBuyerStatus(User $user) : ?Item
    {
        $buyerStatus = null;

        if ($user->relationLoaded('balances')) {
            if ($user->getBuyerBalance()) {
                $buyerStatus = $user->getBuyerBalance()
                    ->getStatus();
            }
        }

        return $buyerStatus ? $this->item($buyerStatus, new UserBalanceStatusTransformer) : null;
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
