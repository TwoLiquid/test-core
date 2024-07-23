<?php

namespace App\Repositories\User\Interfaces;

use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MongoDb\User\Request\Deactivation\UserDeactivationRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface UserDeactivationRequestRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserDeactivationRequestRepositoryInterface
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
     * @return UserDeactivationRequest|null
     */
    public function findPendingForUser(
        User $user
    ) : ?UserDeactivationRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return UserDeactivationRequest|null
     */
    public function findLastForUser(
        User $user
    ) : ?UserDeactivationRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param bool $shown
     *
     * @return UserDeactivationRequest|null
     */
    public function findLastShownForUser(
        User $user,
        bool $shown
    ) : ?UserDeactivationRequest;

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
     * @return UserDeactivationRequest|null
     */
    public function store(
        User $user,
        ?string $reason,
        AccountStatusListItem $accountStatusListItem,
        AccountStatusListItem $previousAccountStatusListItem
    ) : ?UserDeactivationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param string|null $toastMessageText
     *
     * @return UserDeactivationRequest|null
     */
    public function update(
        UserDeactivationRequest $userDeactivationRequest,
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?string $toastMessageText
    ) : ?UserDeactivationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserDeactivationRequest
     */
    public function updateRequestStatus(
        UserDeactivationRequest $userDeactivationRequest,
        RequestStatusListItem $requestStatusListItem
    ) : UserDeactivationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserDeactivationRequest
     */
    public function updateToastMessageType(
        UserDeactivationRequest $userDeactivationRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : UserDeactivationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param bool $shown
     *
     * @return UserDeactivationRequest
     */
    public function updateShown(
        UserDeactivationRequest $userDeactivationRequest,
        bool $shown
    ) : UserDeactivationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserDeactivationRequest
     */
    public function updateLanguage(
        UserDeactivationRequest $userDeactivationRequest,
        LanguageListItem $languageListItem
    ) : UserDeactivationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param Admin $admin
     *
     * @return UserDeactivationRequest
     */
    public function updateAdmin(
        UserDeactivationRequest $userDeactivationRequest,
        Admin $admin
    ) : UserDeactivationRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return bool
     */
    public function delete(
        UserDeactivationRequest $userDeactivationRequest
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
