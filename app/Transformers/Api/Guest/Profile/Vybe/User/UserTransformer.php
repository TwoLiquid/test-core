<?php

namespace App\Transformers\Api\Guest\Profile\Vybe\User;

use App\Lists\Language\LanguageList;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home\User
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'gender',
        'current_city_place',
        'languages'
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
            'username'   => $user->username,
            'followers'  => $user->subscribers_count,
            'followings' => $user->subscriptions_count,
            'birth_date' => $user->birth_date->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeGender(User $user) : ?Item
    {
        $gender = $user->getGender();

        return $gender ? $this->item($gender, new GenderTransformer) : null;
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
     * @return Collection|null
     */
    public function includeLanguages(User $user) : ?Collection
    {
        $languages = new EloquentCollection();

        if ($user->relationLoaded('languages')) {
            foreach ($user->languages as $language) {
                $languages->push(
                    LanguageList::getItem(
                        $language->language_id
                    )
                );
            }
        }

        return $this->collection($languages, new LanguageTransformer);
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
