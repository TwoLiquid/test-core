<?php

namespace App\Repositories\User\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusListItem;
use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface UserIdVerificationRequestRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserIdVerificationRequestRepositoryInterface
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
     * with an eloquent model by certain
     *
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     */
    public function findPendingForUser(
        User $user
    ) : ?UserIdVerificationRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain
     *
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     */
    public function findLastDeclinedForUser(
        User $user
    ) : ?UserIdVerificationRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     */
    public function findAcceptedForUser(
        User $user
    ) : ?UserIdVerificationRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     */
    public function findLastForUser(
        User $user
    ) : ?UserIdVerificationRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param bool $shown
     *
     * @return UserIdVerificationRequest|null
     */
    public function findLastShownForUser(
        User $user,
        bool $shown
    ) : ?UserIdVerificationRequest;

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
        ?int $page = null
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
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getNotAcceptedForUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getLastDeclinedForUser(
        User $user
    ) : Collection;

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
    public function existsAcceptedForUser(
        User $user
    ) : bool;

    /**
     * This method provides checking row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getForUser(
        User $user
    ) : Collection;

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
     * @param UserIdVerificationStatusListItem $userIdVerificationStatusListItem
     *
     * @return UserIdVerificationRequest|null
     */
    public function store(
        User $user,
        UserIdVerificationStatusListItem $userIdVerificationStatusListItem
    ) : ?UserIdVerificationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     * @param string|null $toastMessageText
     *
     * @return UserIdVerificationRequest|null
     */
    public function update(
        UserIdVerificationRequest $userIdVerificationRequest,
        RequestFieldStatusListItem $requestFieldStatusListItem,
        ?string $toastMessageText
    ) : ?UserIdVerificationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param bool $shown
     *
     * @return UserIdVerificationRequest
     */
    public function updateShown(
        UserIdVerificationRequest $userIdVerificationRequest,
        bool $shown
    ) : UserIdVerificationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserIdVerificationRequest
     */
    public function updateRequestStatus(
        UserIdVerificationRequest $userIdVerificationRequest,
        RequestStatusListItem $requestStatusListItem
    ) : UserIdVerificationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserIdVerificationRequest
     */
    public function updateToastMessageType(
        UserIdVerificationRequest $userIdVerificationRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : UserIdVerificationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserIdVerificationRequest
     */
    public function updateLanguage(
        UserIdVerificationRequest $userIdVerificationRequest,
        LanguageListItem $languageListItem
    ) : UserIdVerificationRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param Admin $admin
     *
     * @return UserIdVerificationRequest
     */
    public function updateAdmin(
        UserIdVerificationRequest $userIdVerificationRequest,
        Admin $admin
    ) : UserIdVerificationRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return bool
     */
    public function delete(
        UserIdVerificationRequest $userIdVerificationRequest
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
