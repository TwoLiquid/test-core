<?php

namespace App\Transformers\Api\General\Dashboard\Purchase;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Purchase
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $userAvatars;

    /**
     * UserTransformer constructor
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
        'avatar'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'        => $user->id,
            'auth_id'   => $user->auth_id,
            'username'  => $user->username,
            'avatar_id' => $user->avatar_id
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
