<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Models\MongoDb\User\Request\Deactivation\UserDeactivationRequest;
use App\Models\MongoDb\User\Request\Deletion\UserDeletionRequest;
use App\Models\MongoDb\User\Request\Unsuspend\UserUnsuspendRequest;
use App\Models\MySql\User\User;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Transformers\Api\Guest\User\User\Language\LanguageListItemTransformer;
use App\Transformers\Api\Guest\User\User\Language\LanguageTransformer;
use App\Transformers\Api\Guest\User\User\PersonalityTrait\PersonalityTraitTransformer;
use App\Transformers\Api\Guest\User\User\Request\UserDeactivationRequestTransformer;
use App\Transformers\Api\Guest\User\User\Request\UserDeletionRequestTransformer;
use App\Transformers\Api\Guest\User\User\Vybe\VybeTransformer;
use App\Transformers\Api\Guest\User\User\Request\UserUnsuspendRequestTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserFullProfileTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class UserFullProfileTransformer extends BaseTransformer
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
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * UserFullProfileTransformer constructor
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

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();
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
        'current_city_place',
        'timezone',
        'language',
        'currency',
        'label',
        'balances',
        'state_status',
        'account_status',
        'user_id_verification_status',
        'following',
        'followers',
        'activities',
        'block_list',
        'you_blocked_list',
        'personality_traits',
        'languages',
        'vybes',
        'favorite_activities',
        'favorite_vybes',
        'user_deactivation_request',
        'user_deletion_request',
        'user_unsuspend_request',
        'visited_users'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'                        => $user->id,
            'auth_id'                   => $user->auth_id,
            'username'                  => $user->username,
            'verified'                  => $user->verified_celebrity,
            'birth_date'                => $user->birth_date ?
                $user->birth_date->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'description'               => $user->description,
            'followers_count'           => $user->subscribers_count,
            'following_count'           => $user->subscriptions_count,
            'vybes_count'               => $user->vybes_count,
            'favorite_vybes_count'      => $user->favorite_vybes_count,
            'favorite_activities_count' => $user->favorite_activities_count,
            'is_followed'               => $this->authUser && $user->subscribers->contains('pivot.user_id', $this->authUser->id),
            'ip_address'                => null,
            'email'                     => $user->email,
            'email_verified_at'         => $user->email_verified_at ?
                $user->email_verified_at->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'streamer_badge'            => $user->streamer_badge,
            'streamer_milestone'        => $user->streamer_milestone,
            'avatar_id'                 => $user->avatar_id,
            'voice_sample_id'           => $user->voice_sample_id,
            'background_id'             => $user->background_id,
            'images_ids'                => $user->images_ids,
            'videos_ids'                => $user->videos_ids
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
        $languageListItem = LanguageList::getItem(
            $user->language_id
        );

        return $languageListItem ? $this->item($languageListItem, new LanguageListItemTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeCurrency(User $user) : ?Item
    {
        $currencyListItem = $user->getCurrency();

        return $currencyListItem ? $this->item($currencyListItem, new CurrencyTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeLabel(User $user) : ?Item
    {
        $userLabel = $user->getLabel();

        return $userLabel ? $this->item($userLabel, new UserLabelTransformer) : null;
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
    public function includeFollowing(User $user) : ?Collection
    {
        $subscriptions = null;

        if ($user->relationLoaded('subscriptions')) {
            $subscriptions = $user->subscriptions;
        }

        return $subscriptions ? $this->collection(
            $subscriptions,
            new UserFollowingTransformer(
                $this->authUser,
                $this->userAvatars,
                $this->userVoiceSamples
            )
        ) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeFollowers(User $user) : ?Collection
    {
        $subscribers = null;

        if ($user->relationLoaded('subscribers')) {
            $subscribers = $user->subscribers;
        }

        return $subscribers ? $this->collection(
            $subscribers,
            new UserFollowerTransformer(
                $this->authUser,
                $this->userAvatars,
                $this->userVoiceSamples
            )
        ) : null;
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
                    $this->activityImages
                )
            ) : null;
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

        return $blockedUsers ? $this->collection($blockedUsers, new UserShortTransformer) : null;
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

        return $usersBlockedYou ? $this->collection($usersBlockedYou, new UserShortTransformer) : null;
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
    public function includeFavoriteActivities(User $user) : ?Collection
    {
        $favoriteActivities = null;

        if ($user->relationLoaded('favoriteActivities')) {
            $favoriteActivities = $user->favoriteActivities;
        }

        return $favoriteActivities ? $this->collection(
            $favoriteActivities,
            new ActivityTransformer(
                $this->activityImages
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

        return $favoriteVybes ? $this->collection(
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
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserDeactivationRequest(User $user) : ?Item
    {
        $userDeactivationRequest = null;

        if ($user->relationLoaded('deactivationRequests')) {

            /** @var UserDeactivationRequest $latestUserDeactivationRequest */
            $latestUserDeactivationRequest = $user->deactivationRequests()
                ->latest()
                ->first();

            if ($latestUserDeactivationRequest) {

                /**
                 * Checking is user deactivation request accepted
                 */
                if ($latestUserDeactivationRequest->getRequestStatus()->isDeclined() &&
                    !$latestUserDeactivationRequest->shown
                ) {
                    $userDeactivationRequest = $latestUserDeactivationRequest;

                    /**
                     * Updating user deactivation request shown flag
                     */
                    $this->userDeactivationRequestRepository->updateShown(
                        $userDeactivationRequest,
                        true
                    );
                } elseif ($latestUserDeactivationRequest->getRequestStatus()->isPending()) {
                    $userDeactivationRequest = $latestUserDeactivationRequest;
                }
            }
        }

        return $userDeactivationRequest ? $this->item($userDeactivationRequest, new UserDeactivationRequestTransformer) : null;
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
        $userDeletionRequest = null;

        if ($user->relationLoaded('deletionRequests')) {

            /** @var UserDeletionRequest $latestUserDeletionRequest */
            $latestUserDeletionRequest = $user->deletionRequests()
                ->latest()
                ->first();

            if ($latestUserDeletionRequest) {

                /**
                 * Checking is user deletion request accepted
                 */
                if ($latestUserDeletionRequest->getRequestStatus()->isDeclined() &&
                    !$latestUserDeletionRequest->shown
                ) {
                    $userDeletionRequest = $latestUserDeletionRequest;

                    /**
                     * Updating user deletion request shown flag
                     */
                    $this->userDeletionRequestRepository->updateShown(
                        $userDeletionRequest,
                        true
                    );
                } elseif ($latestUserDeletionRequest->getRequestStatus()->isPending()) {
                    $userDeletionRequest = $latestUserDeletionRequest;
                }
            }
        }

        return $userDeletionRequest ? $this->item($userDeletionRequest, new UserDeletionRequestTransformer) : null;
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
        $userUnsuspendRequest = null;

        if ($user->relationLoaded('unsuspendRequests')) {

            /** @var UserUnsuspendRequest $latestUserUnsuspendRequest */
            $latestUserUnsuspendRequest = $user->unsuspendRequests()
                ->latest()
                ->first();

            if ($latestUserUnsuspendRequest) {

                /**
                 * Checking is user unsuspend request accepted
                 */
                if ($latestUserUnsuspendRequest->getRequestStatus()->isDeclined() &&
                    !$latestUserUnsuspendRequest->shown
                ) {
                    $userUnsuspendRequest = $latestUserUnsuspendRequest;

                    /**
                     * Updating user unsuspend request shown flag
                     */
                    $this->userUnsuspendRequestRepository->updateShown(
                        $userUnsuspendRequest,
                        true
                    );
                } elseif ($latestUserUnsuspendRequest->getRequestStatus()->isPending()) {
                    $userUnsuspendRequest = $latestUserUnsuspendRequest;
                }
            }
        }

        return $userUnsuspendRequest ? $this->item($userUnsuspendRequest, new UserUnsuspendRequestTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeVisitedUsers(User $user) : ?Collection
    {
        $visitedUsers = null;

        if ($user->relationLoaded('visitedUsers')) {
            $visitedUsers = $user->visitedUsers;
        }

        return $visitedUsers ? $this->collection($visitedUsers, new UserShortTransformer) : null;
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
