<?php

namespace App\Transformers\Api\Admin\User\Information\Follower;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserFollowerTransformer
 *
 * @package App\Transformers\Api\Admin\User\Information\Follower
 */
class UserFollowerTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $userAvatars;

    /**
     * UserFollowerTransformer constructor
     *
     * @param Collection|null $userAvatars
     */
    public function __construct(
        Collection $userAvatars = null
    )
    {
        /** @var Collection userAvatars */
        $this->userAvatars = $userAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'country_place'
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
            'username'        => $user->username,
            'email'           => $user->email,
            'avatar_id'       => $user->avatar_id,
            'followers_count' => $user->subscribers_count,
            'added_at'        => $user->pivot->added_at ?
                $user->pivot->added_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeAvatar(User $user) : ?Item
    {
        $userAvatar = $this->userAvatars?->filter(function ($item) use ($user) {
            return $item->id == $user->avatar_id;
        })->first();

        return $userAvatar ? $this->item($userAvatar, new UserAvatarTransformer) : null;
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
     * @return string
     */
    public function getItemKey() : string
    {
        return 'follower';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'followers';
    }
}
