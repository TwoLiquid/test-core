<?php

namespace App\Transformers\Api\General\Auth\User;

use App\Models\MySql\User\User;
use App\Transformers\Api\General\Auth\User\Language\LanguageTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Auth\User
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'gender',
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
            'id'       => $user->id,
            'auth_id'  => $user->auth_id,
            'username' => $user->username
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
     * @return Collection|null
     */
    public function includeLanguages(User $user) : ?Collection
    {
        $languages = $user->languages;

        return $languages ? $this->collection($languages, new LanguageTransformer) : null;
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
