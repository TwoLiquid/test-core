<?php

namespace App\Repositories\User\Interfaces;

use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface UserProfileRequestRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserProfileRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return Model|null
     */
    public function findById(
        ?string $id
    ) : ?Model;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return UserProfileRequest|null
     */
    public function findPendingForUser(
        User $user
    ) : ?UserProfileRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return bool
     */
    public function existsPendingForUser(
        User $user
    ) : bool;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $username
     *
     * @return bool
     */
    public function existsUsernameForPending(
        string $username
    ) : bool;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return UserProfileRequest|null
     */
    public function findLastForUser(
        User $user
    ) : ?UserProfileRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param bool $shown
     *
     * @return UserProfileRequest|null
     */
    public function findLastShownForUser(
        User $user,
        bool $shown
    ) : ?UserProfileRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return UserProfileRequest|null
     */
    public function findLastDeclinedForUser(
        User $user
    ) : ?UserProfileRequest;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param string|null $requestId
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $userStatusesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?string $requestId,
        ?int $userId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $sales,
        ?array $languagesIds,
        ?array $userStatusesIds,
        ?array $requestStatusesIds,
        ?string $admin,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string|null $requestId
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $userStatusesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?string $requestId,
        ?int $userId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $sales,
        ?array $languagesIds,
        ?array $userStatusesIds,
        ?array $requestStatusesIds,
        ?string $admin,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows number
     * with an eloquent model
     *
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     */
    public function getRequestStatusCount(
        RequestStatusListItem $requestStatusListItem
    ) : int;

    /**
     * This method provides getting rows number
     * with an eloquent model
     *
     * @param array $ids
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     */
    public function getRequestStatusCountByIds(
        array $ids,
        RequestStatusListItem $requestStatusListItem
    ) : int;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param User $user
     * @param AccountStatusListItem|null $accountStatusListItem
     * @param AccountStatusListItem|null $previousAccountStatusListItem
     * @param string|null $username
     * @param string|null $previousUsername
     * @param string|null $birthDate
     * @param string|null $previousBirthDate
     * @param string|null $description
     * @param string|null $previousDescription
     *
     * @return UserProfileRequest|null
     */
    public function store(
        User $user,
        ?AccountStatusListItem $accountStatusListItem,
        ?AccountStatusListItem $previousAccountStatusListItem,
        ?string $username,
        ?string $previousUsername,
        ?string $birthDate,
        ?string $previousBirthDate,
        ?string $description,
        ?string $previousDescription
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param RequestFieldStatusListItem|null $usernameStatus
     * @param RequestFieldStatusListItem|null $birthDateStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $voiceSampleStatus
     * @param RequestFieldStatusListItem|null $avatarStatus
     * @param RequestFieldStatusListItem|null $backgroundStatus
     * @param RequestFieldStatusListItem|null $albumStatus
     * @param string|null $toastMessageText
     *
     * @return UserProfileRequest|null
     */
    public function update(
        UserProfileRequest $userProfileRequest,
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?RequestFieldStatusListItem $usernameStatus,
        ?RequestFieldStatusListItem $birthDateStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $voiceSampleStatus,
        ?RequestFieldStatusListItem $avatarStatus,
        ?RequestFieldStatusListItem $backgroundStatus,
        ?RequestFieldStatusListItem $albumStatus,
        ?string $toastMessageText
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param int|null $voiceSampleId
     * @param int|null $previousVoiceSampleId
     *
     * @return UserProfileRequest|null
     */
    public function updateVoiceSample(
        UserProfileRequest $userProfileRequest,
        ?int $voiceSampleId,
        ?int $previousVoiceSampleId
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param int|null $avatarId
     * @param int|null $previousAvatarId
     *
     * @return UserProfileRequest|null
     */
    public function updateAvatar(
        UserProfileRequest $userProfileRequest,
        ?int $avatarId,
        ?int $previousAvatarId
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param int|null $backgroundId
     * @param int|null $previousBackgroundId
     *
     * @return UserProfileRequest|null
     */
    public function updateBackground(
        UserProfileRequest $userProfileRequest,
        ?int $backgroundId,
        ?int $previousBackgroundId
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param array|null $imagesIds
     * @param array|null $previousImagesIds
     * @param array|null $videosIds
     * @param array|null $previousVideosIds
     *
     * @return UserProfileRequest|null
     */
    public function updateAlbum(
        UserProfileRequest $userProfileRequest,
        ?array $imagesIds,
        ?array $previousImagesIds,
        ?array $videosIds,
        ?array $previousVideosIds
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserProfileRequest|null
     */
    public function updateRequestStatus(
        UserProfileRequest $userProfileRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserProfileRequest|null
     */
    public function updateToastMessageType(
        UserProfileRequest $userProfileRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : ?UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserProfileRequest
     */
    public function updateLanguage(
        UserProfileRequest $userProfileRequest,
        LanguageListItem $languageListItem
    ) : UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param bool $shown
     *
     * @return UserProfileRequest
     */
    public function updateShown(
        UserProfileRequest $userProfileRequest,
        bool $shown
    ) : UserProfileRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     * @param Admin $admin
     *
     * @return UserProfileRequest|null
     */
    public function updateAdmin(
        UserProfileRequest $userProfileRequest,
        Admin $admin
    ) : ?UserProfileRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return bool
     */
    public function delete(
        UserProfileRequest $userProfileRequest
    ) : bool;
}
