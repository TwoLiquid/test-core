<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Lists\Gender\GenderList;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserFollowerTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class UserFollowerTransformer extends BaseTransformer
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
     * UserFollowerTransformer constructor
     *
     * @param User|null $user
     * @param Collection|null $userAvatars
     * @param Collection|null $userVoiceSamples
     */
    public function __construct(
        ?User $user,
        Collection $userAvatars = null,
        Collection $userVoiceSamples = null
    )
    {
        /** @var User user */
        $this->authUser = $user;

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
            'is_followed'     => $this->authUser && $user->subscribers->contains('pivot.user_id', $this->authUser->id)
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
        $gender = GenderList::getItem(
            $user->gender_id
        );

        return $gender ? $this->item($gender, new GenderTransformer) : null;
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
