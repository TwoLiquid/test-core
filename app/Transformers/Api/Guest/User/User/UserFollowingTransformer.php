<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Lists\Gender\GenderList;
use App\Models\MySql\User\User;
use App\Transformers\Api\Guest\User\User\Language\LanguageTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class UserFollowingTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class UserFollowingTransformer extends BaseTransformer
{
    /**
     * @var User|null
     */
    protected ?User $authUser;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVoiceSamples;

    /**
     * UserFollowingTransformer constructor
     *
     * @param User|null $user
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userVoiceSamples
     */
    public function __construct(
        ?User $user,
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
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeLanguages(User $user) : ?Collection
    {
        $languages = null;

        if ($user->relationLoaded('languages')) {
            $languages = $user->languages;
        }

        return $languages ? $this->collection($languages, new LanguageTransformer) : null;
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
