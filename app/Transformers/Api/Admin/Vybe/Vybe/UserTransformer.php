<?php

namespace App\Transformers\Api\Admin\Vybe\Vybe;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Vybe
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_status',
        'timezone',
        'current_city_place',
        'referred_user',
        'suspend_admin'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'             => $user->id,
            'auth_id'        => $user->auth_id,
            'username'       => $user->username,
            'suspend_reason' => $user->suspend_reason,
            'vpn'            => null,
            'duplicated'     => null,
            'signed_up_at'   => $user->signed_up_at ?
                $user->signed_up_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeAccountStatus(User $user) : ?Item
    {
        $accountStatus = $user->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeCurrentCityPlace(User $user) : ?Item
    {
        $currentCityPlace = null;

        if ($user->relationLoaded('currentCityPlace')) {
            $currentCityPlace = $user->currentCityPlace;
        }

        return $currentCityPlace ? $this->item($currentCityPlace, new CityPlaceTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeTimezone(User $user) : ?Item
    {
        $timezone = null;

        if ($user->relationLoaded('timezone')) {
            $timezone = $user->timezone;
        }

        return $timezone ? $this->item($timezone, new TimezoneTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeReferredUser(User $user) : ?Item
    {
        $referredUser = null;

        if ($user->relationLoaded('referredUser')) {
            $referredUser = $user->referredUser;
        }

        return $referredUser ? $this->item($referredUser, new UserShortTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeSuspendAdmin(User $user) : ?Item
    {
        $suspendAdmin = null;

        if ($user->relationLoaded('suspendAdmin')) {
            $suspendAdmin = $user->suspendAdmin;
        }

        return $suspendAdmin ? $this->item($suspendAdmin, new UserShortTransformer) : null;
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
