<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserFollowingTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class UserFollowingTransformer extends BaseTransformer
{
    /**
     * @var User|null
     */
    protected ?User $authUser;

    /**
     * @var Collection|null
     */
    protected ?Collection $userAvatars;

    /**
     * @var Collection|null
     */
    protected ?Collection $userVoiceSamples;

    /**
     * UserFollowingTransformer constructor
     *
     * @param User|null $authUser
     * @param Collection|null $userAvatars
     * @param Collection|null $userVoiceSamples
     */
    public function __construct(
        ?User $authUser,
        Collection $userAvatars = null,
        Collection $userVoiceSamples = null
    )
    {
        /** @var User authUser */
        $this->authUser = $authUser;

        /** @var Collection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var Collection userVoiceSamples */
        $this->userVoiceSamples = $userVoiceSamples;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'voice_sample',
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
            'id'              => $user->id,
            'auth_id'         => $user->auth_id,
            'username'        => $user->username,
            'avatar_id'       => $user->avatar_id,
            'voice_sample_id' => $user->voice_sample_id,
            'followers_count' => $user->subscribers_count,
            'is_followed'     => $this->authUser && $user->subscribers->contains('pivot.user_id', $this->authUser->id),
            'you_blocked'     => $this->authUser && $user->blockList->contains('pivot.blocked_user_id', $this->authUser->id)
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
    public function includeVoiceSample(User $user) : ?Item
    {
        $userVoiceSample = $this->userVoiceSamples?->filter(function ($item) use ($user) {
            return $item->id == $user->voice_sample_id;
        })->first();

        return $userVoiceSample ? $this->item($userVoiceSample, new UserVoiceSampleTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeGender(User $user) : ?Item
    {
        $gender = null;

        if ($user->hide_gender === false) {
            $gender = $user->getGender();
        }

        return $gender ? $this->item($gender, new GenderTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'following';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'following';
    }
}
