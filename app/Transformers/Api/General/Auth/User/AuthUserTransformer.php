<?php

namespace App\Transformers\Api\General\Auth\User;

use App\Lists\Language\LanguageList;
use App\Models\MySql\User\User;
use App\Transformers\Api\General\Auth\User\Balance\UserBalanceTransformer;
use App\Transformers\Api\General\Auth\User\Language\LanguageListItemTransformer;
use App\Transformers\Api\General\Auth\User\Language\LanguageTransformer;
use App\Transformers\Api\General\Auth\User\Request\UserDeletionRequestTransformer;
use App\Transformers\Api\General\Auth\User\Request\UserUnsuspendRequestTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class AuthUserTransformer
 *
 * @package App\Transformers\Api\General\Auth\User
 */
class AuthUserTransformer extends BaseTransformer
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
     * AuthUserTransformer constructor
     *
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userBackgrounds
     */
    public function __construct(
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null
    )
    {
        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var EloquentCollection userBackgrounds */
        $this->userBackgrounds = $userBackgrounds;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'background',
        'timezone',
        'current_city_place',
        'language',
        'currency',
        'state_status',
        'account_status',
        'theme',
        'balances',
        'user_id_verification_status',
        'languages',
        'user_deletion_request',
        'user_unsuspend_request'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'                   => $user->id,
            'auth_id'              => $user->auth_id,
            'username'             => $user->username,
            'email'                => $user->email,
            'birth_date'           => $user->birth_date ? $user->birth_date->format('Y-m-d\TH:i:s.v\Z') : null,
            'suspend_reason'       => $user->suspend_reason,
            'avatar_id'            => $user->avatar_id,
            'background_id'        => $user->background_id,
            'cart_items_count'     => $user->cart_items_count,
            'temporary_deleted_at' => $user->temporary_deleted_at ?
                $user->temporary_deleted_at->format('Y-m-d\TH:i:s.v\Z') :
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
    public function includeTheme(User $user) : ?Item
    {
        $userThemeListItem = $user->getTheme();

        return $userThemeListItem ? $this->item($userThemeListItem, new UserThemeTransformer) : null;
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
     */
    public function includeUserUnsuspendRequest(User $user) : ?Item
    {
        if ($user->getAccountStatus()->isSuspended()) {
            $userUnsuspendRequest = $user->unsuspendRequest;

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
     */
    public function includeUserDeletionRequest(User $user) : ?Item
    {
        if ($user->getAccountStatus()->isSuspended()) {
            $userDeletionRequest = $user->deletionRequest;

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
