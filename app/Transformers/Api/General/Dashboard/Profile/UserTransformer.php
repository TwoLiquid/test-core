<?php

namespace App\Transformers\Api\General\Dashboard\Profile;

use App\Exceptions\DatabaseException;
use App\Models\MySql\User\User;
use App\Repositories\User\UserProfileRequestRepository;
use App\Transformers\Api\General\Dashboard\Profile\Language\LanguageTransformer;
use App\Transformers\Api\General\Dashboard\Profile\PersonalityTrait\PersonalityTraitTransformer;
use App\Transformers\Api\General\Dashboard\Profile\User\ProfileRequest\UserProfileRequestTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile
 */
class UserTransformer extends BaseTransformer
{
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
    protected ?EloquentCollection $userProfileRequestAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestBackgrounds;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestVoiceSamples;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestVideos;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * UserTransformer constructor
     *
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userBackgrounds
     * @param EloquentCollection|null $userVoiceSamples
     * @param EloquentCollection|null $userImages
     * @param EloquentCollection|null $userVideos
     * @param EloquentCollection|null $userProfileRequestAvatars
     * @param EloquentCollection|null $userProfileRequestBackgrounds
     * @param EloquentCollection|null $userProfileRequestVoiceSamples
     * @param EloquentCollection|null $userProfileRequestImages
     * @param EloquentCollection|null $userProfileRequestVideos
     */
    public function __construct(
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null,
        EloquentCollection $userVoiceSamples = null,
        EloquentCollection $userImages = null,
        EloquentCollection $userVideos = null,
        EloquentCollection $userProfileRequestAvatars = null,
        EloquentCollection $userProfileRequestBackgrounds = null,
        EloquentCollection $userProfileRequestVoiceSamples = null,
        EloquentCollection $userProfileRequestImages = null,
        EloquentCollection $userProfileRequestVideos = null
    )
    {
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

        /** @var EloquentCollection userProfileRequestAvatars */
        $this->userProfileRequestAvatars = $userProfileRequestAvatars;

        /** @var EloquentCollection userProfileRequestBackgrounds */
        $this->userProfileRequestBackgrounds = $userProfileRequestBackgrounds;

        /** @var EloquentCollection userProfileRequestVoiceSamples */
        $this->userProfileRequestVoiceSamples = $userProfileRequestVoiceSamples;

        /** @var EloquentCollection userProfileRequestImages */
        $this->userProfileRequestImages = $userProfileRequestImages;

        /** @var EloquentCollection userProfileRequestVideos */
        $this->userProfileRequestVideos = $userProfileRequestVideos;

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();
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
        'account_status',
        'gender',
        'current_city_place',
        'personality_traits',
        'languages',
        'user_profile_request'
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
            'hide_gender'     => $user->hide_gender,
            'birth_date'      => $user->birth_date ? $user->birth_date->format('Y-m-d\TH:i:s.v\Z') : null,
            'hide_age'        => $user->hide_age,
            'description'     => $user->description,
            'top_vybers'      => $user->top_vybers,
            'hide_reviews'    => $user->hide_reviews,
            'hide_location'   => $user->hide_location,
            'followers_count' => $user->subscribers_count,
            'following_count' => $user->subscriptions_count,
            'avatar_id'       => $user->avatar_id,
            'voice_sample_id' => $user->voice_sample_id,
            'background_id'   => $user->background_id,
            'images_ids'      => $user->images_ids,
            'videos_ids'      => $user->videos_ids
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
    public function includeAccountStatus(User $user) : ?Item
    {
        $accountStatus = $user->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
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
    public function includeCurrentCityPlace(User $user) : ?Item
    {
        $currentCityPlace = null;

        if ($user->relationLoaded('currentCityPlace')) {
            $currentCityPlace = $user->currentCityPlace;
        }

        return $currentCityPlace ? $this->item($currentCityPlace, new CityPlaceTransformer) : null;
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
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserProfileRequest(User $user) : ?Item
    {
        $userProfileRequest = null;

        if ($user->profileRequest) {
            $lastUserProfileRequest = $user->profileRequest;

            if ($lastUserProfileRequest->shown === false) {
                if ($lastUserProfileRequest->getRequestStatus()->isAccepted() ||
                    $lastUserProfileRequest->getRequestStatus()->isCanceled()
                ) {
                    $this->userProfileRequestRepository->updateShown(
                        $lastUserProfileRequest,
                        true
                    );
                }

                $userProfileRequest = $lastUserProfileRequest;
            }
        }

        return $userProfileRequest ? $this->item(
            $userProfileRequest,
            new UserProfileRequestTransformer(
                $this->userProfileRequestAvatars,
                $this->userProfileRequestBackgrounds,
                $this->userProfileRequestVoiceSamples,
                ($this->userProfileRequestImages && $this->userImages) ?
                    $this->userProfileRequestImages->diff($this->userImages) :
                    null,
                ($this->userProfileRequestVideos && $this->userVideos) ?
                    $this->userProfileRequestVideos->diff($this->userVideos) :
                    null
            )
        ) : null;
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
