<?php

namespace App\Repositories\User\Interfaces;

use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MongoDb\User\Request\Deletion\UserDeletionRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface UserDeletionRequestRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserDeletionRequestRepositoryInterface
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
     * @return UserDeletionRequest|null
     */
    public function findPendingForUser(
        User $user
    ) : ?UserDeletionRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return UserDeletionRequest|null
     */
    public function findLastForUser(
        User $user
    ) : ?UserDeletionRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param bool $shown
     *
     * @return UserDeletionRequest|null
     */
    public function findLastShownForUser(
        User $user,
        bool $shown
    ) : ?UserDeletionRequest;

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
     * This method provides checking row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function existsForUser(
        User $user
    ) : bool;

    /**
     * This method provides checking row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function existsPendingForUser(
        User $user
    ) : bool;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param User $user
     * @param string|null $reason
     * @param AccountStatusListItem $accountStatusListItem
     * @param AccountStatusListItem $previousAccountStatusListItem
     *
     * @return UserDeletionRequest|null
     */
    public function store(
        User $user,
        ?string $reason,
        AccountStatusListItem $accountStatusListItem,
        AccountStatusListItem $previousAccountStatusListItem
    ) : ?UserDeletionRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeletionRequest $userDeletionRequest
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param string|null $toastMessageText
     *
     * @return UserDeletionRequest|null
     */
    public function update(
        UserDeletionRequest $userDeletionRequest,
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?string $toastMessageText
    ) : ?UserDeletionRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeletionRequest $userDeletionRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserDeletionRequest
     */
    public function updateRequestStatus(
        UserDeletionRequest $userDeletionRequest,
        RequestStatusListItem $requestStatusListItem
    ) : UserDeletionRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeletionRequest $userDeletionRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserDeletionRequest
     */
    public function updateToastMessageType(
        UserDeletionRequest $userDeletionRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : UserDeletionRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeletionRequest $userDeletionRequest
     * @param bool $shown
     *
     * @return UserDeletionRequest
     */
    public function updateShown(
        UserDeletionRequest $userDeletionRequest,
        bool $shown
    ) : UserDeletionRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeletionRequest $userDeletionRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserDeletionRequest
     */
    public function updateLanguage(
        UserDeletionRequest $userDeletionRequest,
        LanguageListItem $languageListItem
    ) : UserDeletionRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeletionRequest $userDeletionRequest
     * @param Admin $admin
     *
     * @return UserDeletionRequest
     */
    public function updateAdmin(
        UserDeletionRequest $userDeletionRequest,
        Admin $admin
    ) : UserDeletionRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return bool
     */
    public function delete(
        UserDeletionRequest $userDeletionRequest
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteForUser(
        User $user
    ) : bool;
}
