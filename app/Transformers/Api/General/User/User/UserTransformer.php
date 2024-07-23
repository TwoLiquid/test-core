<?php

namespace App\Transformers\Api\General\User\User;

use App\Models\MySql\User\User;
use App\Transformers\Api\General\User\User\Language\LanguageTransformer;
use App\Transformers\Api\General\User\User\PersonalityTrait\PersonalityTraitTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\User\User
 */
class UserTransformer extends BaseTransformer
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
    protected ?EloquentCollection $userBackgrounds;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVoiceSamples;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVideos;

    /**
     * UserTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userBackgrounds
     * @param EloquentCollection|null $userVoiceSamples
     * @param EloquentCollection|null $userImages
     * @param EloquentCollection|null $userVideos
     */
    public function __construct(
        User $user,
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null,
        EloquentCollection $userVoiceSamples = null,
        EloquentCollection $userImages = null,
        EloquentCollection $userVideos = null
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var EloquentCollection userBackgrounds */
        $this->userBackgrounds = $userBackgrounds;

        /** @var EloquentCollection userVoiceSamples */
        $this->userVoiceSamples = $userVoiceSamples;

        /** @var EloquentCollection userImages */
        $this->userImages = $userImages;

        /** @var EloquentCollection userVideos */
        $this->userVideos = $userVideos;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'background',
        'voice_sample',
        'images',
        'videos',
        'gender',
        'subscriptions',
        'subscribers',
        'languages',
        'personality_traits',
        'block_list',
        'recent_visits',
        'account_status'
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
            'followers_count' => $user->subscribers_count ? $user->subscribers_count : null,
            'vybes_count'     => $user->vybes_count ? $user->vybes_count : null,
            'is_followed'     => $user->subscribers->contains('pivot.user_id', $this->authUser->id)
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
    public function includeBackground(User $user) : ?Item
    {
        $userBackground = $this->userBackgrounds?->filter(function ($item) use ($user) {
            return $item->id == $user->background_id;
        })->first();

        return $userBackground ? $this->item($userBackground, new UserBackgroundTransformer) : null;
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
     * @return Collection|null
     */
    public function includeImages(User $user) : ?Collection
    {
        $userImages = $this->userImages?->filter(function ($item) use ($user) {
            return !is_null($user->images_ids) && in_array($item->id, $user->images_ids);
        });

        return $userImages ? $this->collection($userImages, new UserImageTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeVideos(User $user) : ?Collection
    {
        $userVideos = $this->userVideos?->filter(function ($item) use ($user) {
            return !is_null($user->videos_ids) && in_array($item->id, $user->videos_ids);
        });

        return $userVideos ? $this->collection($userVideos, new UserVideoTransformer) : null;
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
    public function includeSubscriptions(User $user) : ?Collection
    {
        $subscriptions = null;

        if ($user->relationLoaded('subscriptions')) {
            $subscriptions = $user->subscriptions;
        }

        return $subscriptions ? $this->collection($subscriptions, new UserFollowingTransformer($this->authUser)) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeSubscribers(User $user) : ?Collection
    {
        $subscribers = null;

        if ($user->relationLoaded('subscribers')) {
            $subscribers = $user->subscribers;
        }

        return $subscribers ? $this->collection($subscribers, new UserFollowerTransformer($this->authUser)) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeRecentVisits(User $user) : ?Collection
    {
        $recentVisits = null;

        if ($user->relationLoaded('visitedUsers')) {
            $recentVisits = $user->visitedUsers;
        }

        return $recentVisits ? $this->collection($recentVisits, new UserRecentVisitTransformer) : null;
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
     * @param User $user
     *
     * @return Collection|null
     */
    public function includePersonalityTraits(User $user) : ?Collection
    {
        $traits = null;

        if ($user->relationLoaded('personalityTraits')) {
            $traits = $user->personalityTraits;
        }

        return $traits ? $this->collection($traits, new PersonalityTraitTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeBlockList(User $user) : ?Collection
    {
        $users = null;

        if ($user->relationLoaded('blockList')) {
            $users = $user->blockList;
        }

        return $users ? $this->collection($users, new UserShortTransformer) : null;
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
