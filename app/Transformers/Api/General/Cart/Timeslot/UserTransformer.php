<?php

namespace App\Transformers\Api\General\Cart\Timeslot;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Cart\Timeslot
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $authUser;

    /**
     * @var Collection|null
     */
    protected ?Collection $userAvatars;

    /**
     * UserTransformer constructor
     *
     * @param User $user
     * @param Collection|null $userAvatars
     */
    public function __construct(
        User $user,
        Collection $userAvatars = null
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var Collection userAvatars */
        $this->userAvatars = $userAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'gender',
        'label',
        'account_status',
        'state_status'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'          => $user->id,
            'auth_id'     => $user->auth_id,
            'username'    => $user->username,
            'avatar_id'   => $user->avatar_id,
            'vybes_count' => $user->vybes_count,
            'is_followed' => $this->authUser->subscribers->contains('pivot.user_id', '=', $user->id),
            'is_follower' => $this->authUser->subscriptions->contains('pivot.subscription_id', '=', $user->id),
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
     * @param User $user
     *
     * @return Item|null
     */
    public function includeLabel(User $user) : ?Item
    {
        $label = $user->getLabel();

        return $label ? $this->item($label, new UserLabelTransformer) : null;
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
    public function includeStateStatus(User $user) : ?Item
    {
        $stateStatus = $user->getStateStatus();

        return $stateStatus ? $this->item($stateStatus, new StateStatusTransformer) : null;
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
