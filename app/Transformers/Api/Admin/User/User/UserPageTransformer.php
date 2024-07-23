<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Exceptions\DatabaseException;
use App\Models\MySql\User\User;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Transformers\Api\Admin\User\User\Language\LanguageListItemTransformer;
use App\Transformers\Api\Admin\User\User\Language\LanguageTransformer;
use App\Transformers\Api\Admin\User\User\PersonalityTrait\PersonalityTraitTransformer;
use App\Transformers\Api\Admin\User\User\Request\Deactivation\UserDeactivationRequestTransformer;
use App\Transformers\Api\Admin\User\User\Request\Deletion\UserDeletionRequestTransformer;
use App\Transformers\Api\Admin\User\User\Request\Profile\UserProfileRequestTransformer;
use App\Transformers\Api\Admin\User\User\Request\Unsuspend\UserUnsuspendRequestTransformer;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class UserPageTransformer extends BaseTransformer
{
    /**
     * @var bool|null
     */
    protected ?bool $requests;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserBackgroundRepository
     */
    protected UserBackgroundRepository $userBackgroundRepository;

    /**
     * @var UserVoiceSampleRepository
     */
    protected UserVoiceSampleRepository $userVoiceSampleRepository;

    /**
     * @var UserImageRepository
     */
    protected UserImageRepository $userImageRepository;

    /**
     * @var UserVideoRepository
     */
    protected UserVideoRepository $userVideoRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

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
     * UserPageTransformer constructor
     *
     * @param bool|null $requests
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userBackgrounds
     * @param EloquentCollection|null $userVoiceSamples
     * @param EloquentCollection|null $userImages
     * @param EloquentCollection|null $userVideos
     */
    public function __construct(
        ?bool $requests = false,
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null,
        EloquentCollection $userVoiceSamples = null,
        EloquentCollection $userImages = null,
        EloquentCollection $userVideos = null
    )
    {
        /** @var bool requests */
        $this->requests = $requests;

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserVideoRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserBackgroundRepository userBackgroundRepository */
        $this->userBackgroundRepository = new UserBackgroundRepository();

        /** @var UserVoiceSampleRepository userVoiceSampleRepository */
        $this->userVoiceSampleRepository = new UserVoiceSampleRepository();

        /** @var UserImageRepository userImageRepository */
        $this->userImageRepository = new UserImageRepository();

        /** @var UserVideoRepository userVideoRepository */
        $this->userVideoRepository = new UserVideoRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

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
        'balances',
        'referred_user',
        'state_status',
        'account_status',
        'user_id_verification_status',
        'timezone',
        'current_city_place',
        'language',
        'currency',
        'gender',
        'languages',
        'personality_traits',
        'requests',
        'user_profile_request',
        'user_deactivation_request',
        'user_unsuspend_request',
        'user_deletion_request'
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
            'hide_gender'        => $user->hide_gender,
            'birth_date'         => $user->birth_date ? $user->birth_date->format('Y-m-d\TH:i:s.v\Z') : null,
            'hide_age'           => $user->hide_age,
            'hide_location'      => $user->hide_location,
            'verified_partner'   => $user->verified_partner,
            'streamer_badge'     => $user->streamer_badge,
            'streamer_milestone' => $user->streamer_milestone,
            'description'        => $user->description,
            'suspend_reason'     => $user->suspend_reason,
            'receive_news'       => $user->receive_news,
            'vpn_used'           => $user->vpn_used,
            'avatar_id'          => $user->avatar_id,
            'voice_sample_id'    => $user->voice_sample_id,
            'background_id'      => $user->background_id,
            'images_ids'         => $user->images_ids,
            'videos_ids'         => $user->videos_ids,
            'signed_up_at'       => $user->signed_up_at ? $user->signed_up_at->format('Y-m-d\TH:i:s.v\Z') : null,
            'ip_address'         => null,
            'email'              => $user->email,
            'email_verified_at'  => $user->email_verified_at ?
                Carbon::parse($user->email_verified_at)->format('Y-m-d\TH:i:s.v\Z') :
                null
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
     * @return Collection|null
     */
    public function includeBalances(User $user) : ?Collection
    {
        $balances = null;

        if ($user->relationLoaded('balances')) {
            $balances = $user->balances;
        }

        return $balances ? $this->collection($balances, new UserBalanceTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeReferredUser(User $user) : ?Item
    {
        $referredUser = null;

        if ($user->relationLoaded('referredUser')) {
            $referredUser = $user->referredUser;
        }

        return $referredUser ? $this->item($referredUser, new UserTransformer) : null;
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
     *
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
    public function includeLanguage(User $user) : ?Item
    {
        $languageStatus = $user->getLanguage();

        return $languageStatus ? $this->item($languageStatus, new LanguageListItemTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeCurrency(User $user) : ?Item
    {
        $currencyStatus = $user->getCurrency();

        return $currencyStatus ? $this->item($currencyStatus, new CurrencyTransformer) : null;
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
     * @return Item|null
     */
    public function includeRequests(User $user) : ?Item
    {
        if ($this->requests === true) {
            return $this->item($user, new UserRequestTransformer);
        }

        return null;
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
        if ($this->requests === true) {
            $userProfileRequest = $this->userProfileRequestRepository->findLastForUser(
                $user
            );

            if ($userProfileRequest) {
                if ($userProfileRequest->getRequestStatus()->isPending() ||
                    $userProfileRequest->getRequestStatus()->isDeclined()
                ) {
                    return $this->item($userProfileRequest, new UserProfileRequestTransformer(
                        $this->userAvatarRepository->getByRequests(
                            new EloquentCollection([$userProfileRequest])
                        ),
                        $this->userBackgroundRepository->getByRequests(
                            new EloquentCollection([$userProfileRequest])
                        ),
                        $this->userVoiceSampleRepository->getByRequests(
                            new EloquentCollection([$userProfileRequest])
                        ),
                        $this->userImageRepository->getByRequests(
                            new EloquentCollection([$userProfileRequest])
                        ),
                        $this->userVideoRepository->getByRequests(
                            new EloquentCollection([$userProfileRequest])
                        )
                    ));
                }
            }
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserDeactivationRequest(User $user) : ?Item
    {
        if ($this->requests === true) {
            $userDeactivationRequest = $this->userDeactivationRequestRepository->findLastForUser(
                $user
            );

            if ($userDeactivationRequest) {
                if ($userDeactivationRequest->getRequestStatus()->isPending() ||
                    $userDeactivationRequest->getRequestStatus()->isDeclined()
                ) {
                    return $this->item($userDeactivationRequest, new UserDeactivationRequestTransformer);
                }
            }
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserUnsuspendRequest(User $user) : ?Item
    {
        if ($this->requests === true) {
            $userUnsuspendRequest = $this->userUnsuspendRequestRepository->findPendingForUser(
                $user
            );

            if ($userUnsuspendRequest) {
                if ($userUnsuspendRequest->getRequestStatus()->isPending() ||
                    $userUnsuspendRequest->getRequestStatus()->isDeclined()
                ) {
                    return $this->item($userUnsuspendRequest, new UserUnsuspendRequestTransformer);
                }
            }
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserDeletionRequest(User $user) : ?Item
    {
        if ($this->requests === true) {
            $userDeletionRequest = $this->userDeletionRequestRepository->findLastForUser(
                $user
            );

            if ($userDeletionRequest) {
                if ($userDeletionRequest->getRequestStatus()->isPending() ||
                    $userDeletionRequest->getRequestStatus()->isDeclined()
                ) {
                    return $this->item($userDeletionRequest, new UserDeletionRequestTransformer);
                }
            }
        }

        return null;
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
