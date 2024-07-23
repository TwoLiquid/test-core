<?php

namespace App\Transformers\Api\General\Auth\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Transformers\Api\General\Auth\User\Language\LanguageTransformer;

/**
 * Class UserFollowerTransformer
 *
 * @package App\Transformers\Api\General\Auth\User
 */
class UserFollowerTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $authUser;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVoiceSamples;

    /**
     * UserFollowerTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userVoiceSamples
     */
    public function __construct(
        User $user,
        EloquentCollection $userAvatars = null,
        EloquentCollection $userVoiceSamples = null
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var EloquentCollection userVoiceSamples */
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
            'is_followed'     => $user->subscribers->contains('pivot.user_id', $this->authUser->id),
            'you_blocked'     => $user->blockList->contains('pivot.blocked_user_id', $this->authUser->id)
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
