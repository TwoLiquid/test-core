<?php

namespace App\Transformers\Api\General\Profile\Vybe;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Profile\Vybe
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'gender'
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
