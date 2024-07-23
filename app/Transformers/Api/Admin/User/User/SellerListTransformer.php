<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class SellerListTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class SellerListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place',
        'account_status',
        'seller_status'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'         => $user->id,
            'auth_id'    => $user->auth_id,
            'account_id' => $user->id,
            'seller_id'  => $user->getSellerBalance()->full_id,
            'username'   => $user->username,
            'balance'    => $user->getSellerBalance()->amount,
            'sold_items' => null,
            'total_sold' => null,
            'vybes'      => null,
            'disputes'   => null
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
    public function includeSellerStatus(User $user) : ?Item
    {
        $sellerStatus = null;

        if ($user->relationLoaded('balances')) {
            if ($user->getSellerBalance()) {
                $sellerStatus = $user->getSellerBalance()
                    ->getStatus();
            }
        }

        return $sellerStatus ? $this->item($sellerStatus, new UserBalanceStatusTransformer) : null;
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
