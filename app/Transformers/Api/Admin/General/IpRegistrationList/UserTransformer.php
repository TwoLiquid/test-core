<?php

namespace App\Transformers\Api\Admin\General\IpRegistrationList;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Admin\General\IpRegistrationList
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_status',
        'current_city_place'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'           => $user->id,
            'auth_id'      => $user->auth_id,
            'username'     => $user->username,
            'signed_up_at' => $user->signed_up_at ?
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
