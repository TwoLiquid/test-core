<?php

namespace App\Transformers\Api\General\Cart\Vybe\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Cart\Vybe\User
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $userAvatars;

    /**
     * @param Collection|null $userAvatars
     */
    public function __construct(
        ?Collection $userAvatars = null
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
