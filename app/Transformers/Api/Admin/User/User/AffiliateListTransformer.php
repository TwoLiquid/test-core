<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class AffiliateListTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class AffiliateListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place',
        'account_status',
        'affiliate_status'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'                 => $user->id,
            'auth_id'            => $user->auth_id,
            'account_id'         => $user->id,
            'affiliate_id'       => $user->getAffiliateBalance()->full_id,
            'username'           => $user->username,
            'affiliates'         => null,
            'balance'            => $user->getAffiliateBalance()->amount,
            'withdrawn_amount'   => null,
            'total_earned'       => null,
            'conversion'         => null,
            'pending_commission' => null
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
    public function includeAffiliateStatus(User $user) : ?Item
    {
        $affiliateStatus = null;

        if ($user->relationLoaded('balances')) {
            if ($user->getAffiliateBalance()) {
                $affiliateStatus = $user->getAffiliateBalance()
                    ->getStatus();
            }
        }

        return $affiliateStatus ? $this->item($affiliateStatus, new UserBalanceStatusTransformer) : null;
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
