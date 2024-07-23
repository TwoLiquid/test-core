<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Models\MySql\User\User;
use App\Transformers\Api\Guest\Profile\Home\Language\LanguageTransformer;
use App\Transformers\Api\Guest\Profile\Home\PersonalityTrait\PersonalityTraitTransformer;
use App\Transformers\Api\Guest\Profile\Home\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserProfileTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class UserProfileTransformer extends BaseTransformer
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
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVideos;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * UserProfileTransformer constructor
     *
     * @param User|null $user
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userBackgrounds
     * @param EloquentCollection|null $userVoiceSamples
     * @param EloquentCollection|null $userImages
     * @param EloquentCollection|null $userVideos
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $activityImages
     */
    public function __construct(
        ?User $user,
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null,
        EloquentCollection $userVoiceSamples = null,
        EloquentCollection $userImages = null,
        EloquentCollection $userVideos = null,
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null,
        EloquentCollection $activityImages = null
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

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;
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
        'timezone',
        'current_city_place',
        'label',
        'state_status',
        'account_status',
        'user_id_verification_status',
        'block_list',
        'you_blocked_list',
        'personality_traits',
        'languages',
        'activities',
        'vybes',
        'favorite_vybes',
        'balances'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'                 => $user->id,
            'auth_id'            => $user->auth_id,
            'username'           => $user->username,
            'followers_count'    => $user->subscribers_count,
            'following_count'    => $user->subscriptions_count,
            'birth_date'         => $user->birth_date->format('Y-m-d\TH:i:s.v\Z'),
            'verified_celebrity' => $user->verified_celebrity,
            'description'        => $user->description,
            'age'                => $user->hide_age === false ?
                Carbon::parse($user->birth_date)->age :
                null,
            'you_blocked'        => $this->authUser && $user->blockList->contains('pivot.blocked_user_id', '=', $this->authUser->id),
            'is_followed'        => $this->authUser && $user->subscribers->contains('pivot.user_id', '=', $this->authUser->id),
            'avatar_id'          => $user->avatar_id,
            'voice_sample_id'    => $user->voice_sample_id,
            'background_id'      => $user->background_id,
            'images_ids'         => $user->images_ids,
            'videos_ids'         => $user->videos_ids,
            'avatar'             => null,
            'background'         => null,
            'voice_sample'       => null,
            'reviews'            => null
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

        return $userImages ? $this->collection($userImages, new UserImageTransformer($this->authUser)) : null;
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
        $gender = null;

        if ($user->hide_gender === false) {
            $gender = $user->getGender();
        }

        return $gender ? $this->item($gender, new GenderTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeCurrentCityPlace(User $user) : ?Item
    {
        $currentCityPlace = null;

        if ($user->hide_location === false) {
            if ($user->relationLoaded('currentCityPlace')) {
                $currentCityPlace = $user->currentCityPlace;
            }
        }

        return $currentCityPlace ? $this->item($currentCityPlace, new CityPlaceTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeTimezone(User $user) : ?Item
    {
        $timezone = null;

        if ($user->relationLoaded('timezone')) {
            $timezone = $user->timezone;
        }

        return $timezone ? $this->item($timezone, new TimezoneTransformer) : null;
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
    public function includeStateStatus(User $user) : ?Item
    {
        $stateStatus = $user->getStateStatus();

        return $stateStatus ? $this->item($stateStatus, new UserStateStatusTransformer) : null;
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
     * ]
     * @return Item|null
     */
    public function includeUserIdVerificationStatus(User $user) : ?Item
    {
        $userIdVerificationStatus = $user->getIdVerificationStatus();

        return $userIdVerificationStatus ? $this->item($userIdVerificationStatus, new UserIdVerificationStatusTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeBlockList(User $user) : ?Collection
    {
        $blockedUsers = null;

        if ($user->relationLoaded('blockList')) {
            $blockedUsers = $user->blockList;
        }

        return $blockedUsers ? $this->collection($blockedUsers, new BlockedUserTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeYouBlockedList(User $user) : ?Collection
    {
        $usersBlockedYou = null;

        if ($user->relationLoaded('youBlockedList')) {
            $usersBlockedYou = $user->youBlockedList;
        }

        return $usersBlockedYou ? $this->collection($usersBlockedYou, new BlockedUserTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includePersonalityTraits(User $user) : ?Collection
    {
        $personalityTraits = null;

        if ($user->relationLoaded('personalityTraits')) {
            $personalityTraits = $user->personalityTraits;
        }

        return $personalityTraits ? $this->collection($personalityTraits, new PersonalityTraitTransformer) : null;
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
    public function includeActivities(User $user) : ?Collection
    {
        $activities = null;

        if ($user->relationLoaded('activities')) {
            $activities = $user->activities;
        }

        return $activities ?
            $this->collection(
                $activities,
                new ActivityTransformer(
                    $user,
                    $this->activityImages
                )
            ) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeVybes(User $user) : ?Collection
    {
        $vybes = null;

        if ($user->relationLoaded('vybes')) {
            $vybes = $user->vybes;
        }

        return $vybes ? $this->collection(
            $vybes,
            new VybeTransformer(
                $this->authUser,
                $this->vybeImages,
                $this->vybeVideos
            )
        ) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeFavoriteVybes(User $user) : ?Collection
    {
        $favoriteVybes = null;

        if ($user->relationLoaded('favoriteVybes')) {
            $favoriteVybes = $user->favoriteVybes;
        }

        return $favoriteVybes ?
            $this->collection(
                $favoriteVybes,
                new VybeTransformer(
                    $this->authUser,
                    $this->vybeImages,
                    $this->vybeVideos
                )
            ) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeBalances(User $user) : ?Collection
    {
        $userBalances = null;

        if ($user->relationLoaded('balances')) {
            $userBalances = $user->balances;
        }

        return $userBalances ? $this->collection($userBalances, new UserBalanceTransformer) : null;
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
