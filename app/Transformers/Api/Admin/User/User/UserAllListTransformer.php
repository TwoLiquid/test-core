<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserAllListTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class UserAllListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_status',
        'buyer_status',
        'seller_status',
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
            'id'              => $user->id,
            'auth_id'         => $user->auth_id,
            'account_id'      => $user->id,
            'username'        => $user->username,
            'created_time'    => $user->created_at->format('H:i'),
            'created_date'    => $user->created_at->format('Y-m-d\TH:i:s.v\Z'),
            'followers_count' => $user->subscribers_count ? $user->subscribers_count : null
        ];
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
