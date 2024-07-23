<?php

namespace App\Repositories\User\Interfaces;

use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Currency\CurrencyListItem;
use App\Lists\Gender\GenderListItem;
use App\Lists\Language\LanguageListItem;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusListItem;
use App\Lists\User\State\Status\UserStateStatusListItem;
use App\Lists\User\Theme\UserThemeListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Payment\PaymentMethodField;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Timeslot;
use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface UserRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return User|null
     */
    public function findById(
        ?int $id
    ) : ?User;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param int|null $id
     *
     * @return User|null
     */
    public function findByAuthId(
        ?int $id
    ) : ?User;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $username
     *
     * @return User|null
     */
    public function findByUsername(
        string $username
    ) : ?User;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $email
     *
     * @return User|null
     */
    public function findByEmail(
        string $email
    ) : ?User;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return User|null
     */
    public function findByIdForAdmin(
        ?int $id
    ) : ?User;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param PaymentMethodField $paymentMethodField
     * @param User $user
     *
     * @return User|null
     */
    public function findWithPaymentMethodField(
        PaymentMethodField $paymentMethodField,
        User $user
    ) : ?User;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return User
     */
    public function findWithSubscriptions(
        User $user
    ) : User;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getBuyersCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getSellersCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAffiliatesCount() : int;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string $search
     *
     * @return Collection
     */
    public function getAllBySearch(
        string $search
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param int|null $userId
     * @param string|null $username
     * @param string|null $firstName
     * @param string|null $lastName
     * @param int|null $countryId
     * @param int|null $followers
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $statusesIds
     * @param int|null $userBalanceTypeId
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?int $userId,
        ?string $username,
        ?string $firstName,
        ?string $lastName,
        ?int $countryId,
        ?int $followers,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $statusesIds,
        ?int $userBalanceTypeId,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $userId
     * @param string|null $username
     * @param string|null $firstName
     * @param string|null $lastName
     * @param int|null $countryId
     * @param int|null $followers
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $statusesIds
     * @param int|null $userBalanceTypeId
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?int $userId,
        ?string $username,
        ?string $firstName,
        ?string $lastName,
        ?int $countryId,
        ?int $followers,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $statusesIds,
        ?int $userBalanceTypeId,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getAllExcept(
        User $user
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param array|null $userIds
     *
     * @return Collection
     */
    public function getUsersByIds(
        ?array $userIds
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param array|null $authIds
     *
     * @return Collection
     */
    public function getUsersByAuthIds(
        ?array $authIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by limit
     *
     * @param string $search
     * @param int|null $limit
     *
     * @return Collection
     */
    public function getWithGlobalSearchByLimit(
        string $search,
        ?int $limit
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getRecentVisits(
        User $user
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return User|null
     */
    public function getAuthUser(
        User $user
    ) : ?User;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return User|null
     */
    public function getUserDashboardProfile(
        User $user
    ) : ?User;

    /**
     * This method provides getting data
     * with an eloquent model
     *
     * @param User $user
     *
     * @return array
     */
    public function getUserSubscriptionsIds(
        User $user
    ) : array;

    /**
     * This method provides getting data
     * with an eloquent model
     *
     * @param User $user
     *
     * @return array
     */
    public function getUserSubscribersIds(
        User $user
    ) : array;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getUserSubscriptionsPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getUserSubscriptionsBySearchPaginated(
        User $user,
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getUserSubscribersPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getUserSubscribersBySearchPaginated(
        User $user,
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by search
     *
     * @param User $user
     * @param string|null $search
     *
     * @return Collection
     */
    public function getBlockedUsersBySearch(
        User $user,
        ?string $search
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getBlockedUsersPaginated(
        User $user,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param string|null $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getBlockedUsersBySearchPaginated(
        User $user,
        ?string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param array $authIds
     *
     * @return Collection
     */
    public function getUserAccountStatusesByAuthIds(
        array $authIds
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param array|null $activitiesIds
     * @param string|null $username
     *
     * @return Collection
     */
    public function getByActivitiesIds(
        ?array $activitiesIds,
        ?string $username
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param array|null $activitiesIds
     * @param string|null $username
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedByActivitiesIds(
        ?array $activitiesIds,
        ?string $username,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getTopCreators() : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Timeslot $timeslot
     * @param string|null $username
     *
     * @return Collection
     */
    public function getForTimeslot(
        Timeslot $timeslot,
        ?string $username
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param Timeslot $timeslot
     * @param string|null $username
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getForTimeslotPaginated(
        Timeslot $timeslot,
        ?string $username,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides a check query
     *
     * @param string $email
     *
     * @return bool
     */
    public function checkEmailExistence(
        string $email
    ) : bool;

    /**
     * This method provides a check query
     *
     * @param User $user
     * @param string $username
     *
     * @return bool
     */
    public function checkUsernameUniqueness(
        User $user,
        string $username
    ) : bool;

    /**
     * This method provides a check query
     *
     * @param User $user
     * @param string $email
     *
     * @return bool
     */
    public function checkEmailUniqueness(
        User $user,
        string $email
    ) : bool;

    /**
     * This method provides a check query
     *
     * @param User $user
     * @param User $following
     *
     * @return bool
     */
    public function checkFollowingExists(
        User $user,
        User $following
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param int $authId
     * @param string $username
     * @param string $email
     * @param GenderListItem|null $genderListItem
     * @param string|null $birthDate
     * @param bool|null $hideGender
     * @param bool|null $hideAge
     *
     * @return User|null
     */
    public function store(
        int $authId,
        string $username,
        string $email,
        ?GenderListItem $genderListItem,
        ?string $birthDate,
        ?bool $hideGender,
        ?bool $hideAge
    ) : ?User;

    /**
     * @param User $user
     * @param User $visitedUser
     */
    public function storeVisit(
        User $user,
        User $visitedUser
    ) : void;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param AccountStatusListItem|null $accountStatusListItem
     * @param string|null $username
     * @param string|null $birthdate
     * @param string|null $description
     *
     * @return User
     */
    public function updateForProfileRequest(
        User $user,
        ?AccountStatusListItem $accountStatusListItem,
        ?string $username,
        ?string $birthdate,
        ?string $description
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param GenderListItem|null $genderListItem
     * @param CityPlace|null $currentCityPlace
     * @param bool|null $hideGender
     * @param bool|null $hideAge
     * @param bool|null $hideLocation
     * @param bool|null $topVybers
     * @param bool|null $hideReviews
     *
     * @return User
     */
    public function updateForDashboard(
        User $user,
        ?GenderListItem $genderListItem,
        ?CityPlace $currentCityPlace,
        ?bool $hideGender,
        ?bool $hideAge,
        ?bool $hideLocation,
        ?bool $topVybers,
        ?bool $hideReviews
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param AccountStatusListItem|null $accountStatusListItem
     * @param LanguageListItem|null $languageListItem
     * @param CurrencyListItem|null $currencyListItem
     * @param Timezone|null $timezone
     * @param CityPlace|null $currentCityPlace
     * @param GenderListItem|null $genderListItem
     * @param string|null $username
     * @param string|null $email
     * @param bool|null $hideGender
     * @param string|null $birthDate
     * @param bool|null $hideAge
     * @param bool|null $verifiedPartner
     * @param bool|null $streamerBadge
     * @param bool|null $streamerMilestone
     * @param bool|null $hideLocation
     * @param string|null $description
     * @param bool|null $receiveNews
     *
     * @return User
     */
    public function updateForAdmin(
        User $user,
        ?AccountStatusListItem $accountStatusListItem,
        ?LanguageListItem $languageListItem,
        ?CurrencyListItem $currencyListItem,
        ?Timezone $timezone,
        ?CityPlace $currentCityPlace,
        ?GenderListItem $genderListItem,
        ?string $username,
        ?string $email,
        ?bool $hideGender,
        ?string $birthDate,
        ?bool $hideAge,
        ?bool $verifiedPartner,
        ?bool $streamerBadge,
        ?bool $streamerMilestone,
        ?bool $hideLocation,
        ?string $description,
        ?bool $receiveNews
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param string $username
     *
     * @return User
     */
    public function updateUsername(
        User $user,
        string $username
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param string $email
     *
     * @return User
     */
    public function updateEmail(
        User $user,
        string $email
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param UserStateStatusListItem $userStateStatusListItem
     *
     * @return User
     */
    public function updateStateStatus(
        User $user,
        UserStateStatusListItem $userStateStatusListItem
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param UserThemeListItem $userThemeListItem
     *
     * @return User
     */
    public function updateTheme(
        User $user,
        UserThemeListItem $userThemeListItem
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param AccountStatusListItem $accountStatusListItem
     *
     * @return User
     */
    public function updateAccountStatus(
        User $user,
        AccountStatusListItem $accountStatusListItem
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param LanguageListItem $languageListItem
     *
     * @return User
     */
    public function updateLanguage(
        User $user,
        LanguageListItem $languageListItem
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param CurrencyListItem $currencyListItem
     *
     * @return User
     */
    public function updateCurrency(
        User $user,
        CurrencyListItem $currencyListItem
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param string|null $suspendReason
     *
     * @return User
     */
    public function updateSuspendInformation(
        User $user,
        ?string $suspendReason
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param bool $enableFastOrder
     *
     * @return User
     */
    public function updateFastOrder(
        User $user,
        bool $enableFastOrder
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param Timezone $timezone
     *
     * @return User
     */
    public function updateTimezone(
        User $user,
        Timezone $timezone
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param UserIdVerificationStatusListItem $userIdVerificationStatusListItem
     * @param bool|null $verificationSuspended
     *
     * @return User
     */
    public function updateVerification(
        User $user,
        UserIdVerificationStatusListItem $userIdVerificationStatusListItem,
        ?bool $verificationSuspended
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int $loginAttemptsLeft
     *
     * @return User
     */
    public function updateLoginAttempts(
        User $user,
        int $loginAttemptsLeft
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int $emailAttemptsLeft
     *
     * @return User
     */
    public function updateEmailAttempts(
        User $user,
        int $emailAttemptsLeft
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int $passwordAttemptsLeft
     *
     * @return User
     */
    public function updatePasswordAttempts(
        User $user,
        int $passwordAttemptsLeft
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int|null $avatarId
     * @param int|null $backgroundId
     * @param int|null $voiceSampleId
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return User
     */
    public function updateMediaIds(
        User $user,
        ?int $avatarId,
        ?int $backgroundId,
        ?int $voiceSampleId,
        ?array $imagesIds,
        ?array $videosIds
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int|null $avatarId
     *
     * @return User
     */
    public function setAvatarId(
        User $user,
        ?int $avatarId
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int|null $backgroundId
     *
     * @return User
     */
    public function setBackgroundId(
        User $user,
        ?int $backgroundId
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int|null $voiceSampleId
     *
     * @return User
     */
    public function setVoiceSampleId(
        User $user,
        ?int $voiceSampleId
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param array|null $imagesIds
     *
     * @return User
     */
    public function setImagesIds(
        User $user,
        ?array $imagesIds
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     * @param array|null $videosIds
     *
     * @return User
     */
    public function setVideosIds(
        User $user,
        ?array $videosIds
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return User
     */
    public function verifyEmail(
        User $user
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return User
     */
    public function setEmailVerifyToken(
        User $user
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return User
     */
    public function setPasswordResetToken(
        User $user
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return User
     */
    public function establishNextLoginAttempt(
        User $user
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return User
     */
    public function establishNextEmailAttempt(
        User $user
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return User
     */
    public function establishNextPasswordAttempt(
        User $user
    ) : User;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return User
     */
    public function setTemporaryDeletedAt(
        User $user
    ) : User;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param PaymentMethod $payoutMethod
     */
    public function attachPayoutMethod(
        User $user,
        PaymentMethod $payoutMethod
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param PaymentMethod $payoutMethod
     */
    public function detachPayoutMethod(
        User $user,
        PaymentMethod $payoutMethod
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $subscription
     */
    public function attachSubscription(
        User $user,
        User $subscription
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $subscription
     */
    public function detachSubscription(
        User $user,
        User $subscription
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param Vybe $vybe
     */
    public function attachFavoriteVybe(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param array $favoriteVybesIds
     * @param bool|null $detaching
     */
    public function attachFavoriteVybes(
        User $user,
        array $favoriteVybesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param Vybe $vybe
     */
    public function detachFavoriteVybe(
        User $user,
        Vybe $vybe
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param array $favoriteVybesIds
     */
    public function detachFavoriteVybes(
        User $user,
        array $favoriteVybesIds
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param Activity $activity
     */
    public function attachFavoriteActivity(
        User $user,
        Activity $activity
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param array $favoriteActivitiesIds
     * @param bool|null $detaching
     */
    public function attachFavoriteActivities(
        User $user,
        array $favoriteActivitiesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param Activity $activity
     */
    public function detachFavoriteActivity(
        User $user,
        Activity $activity
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param array $favoriteActivitiesIds
     */
    public function detachFavoriteActivities(
        User $user,
        array $favoriteActivitiesIds
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $subscriber
     */
    public function attachSubscriber(
        User $user,
        User $subscriber
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $subscriber
     */
    public function detachSubscriber(
        User $user,
        User $subscriber
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $visitedUser
     */
    public function attachVisitedUser(
        User $user,
        User $visitedUser
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param array $visitedUsersIds
     * @param bool|null $detaching
     */
    public function attachVisitedUsers(
        User $user,
        array $visitedUsersIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $visitedUser
     */
    public function detachVisitedUser(
        User $user,
        User $visitedUser
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param array $visitedUsersIds
     */
    public function detachVisitedUsers(
        User $user,
        array $visitedUsersIds
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $blockedUser
     */
    public function attachBlockedUser(
        User $user,
        User $blockedUser
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param User $user
     * @param User $blockedUser
     */
    public function detachBlockedUser(
        User $user,
        User $blockedUser
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function delete(
        User $user
    ) : bool;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function forceDelete(
        User $user
    ) : bool;
}
