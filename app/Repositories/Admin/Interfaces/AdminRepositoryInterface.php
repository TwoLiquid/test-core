<?php

namespace App\Repositories\Admin\Interfaces;

use App\Lists\Admin\Status\AdminStatusListItem;
use App\Models\MySql\Admin\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface AdminRepositoryInterface
 *
 * @package App\Repositories\Admin\Interfaces
 */
interface AdminRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Admin|null
     */
    public function findById(
        ?int $id
    ) : ?Admin;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param int|null $id
     *
     * @return Admin|null
     */
    public function findByAuthId(
        ?int $id
    ) : ?Admin;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $email
     *
     * @return Admin|null
     */
    public function findByEmail(
        string $email
    ) : ?Admin;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Admin $admin
     * @param string $email
     *
     * @return bool
     */
    public function findByEmailExceptAdmin(
        Admin $admin,
        string $email
    ) : bool;

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
     * with an eloquent model with filters
     *
     * @param string|null $name
     * @param string|null $email
     * @param array|null $rolesIds
     * @param int|null $statusId
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?string $name,
        ?string $email,
        ?array $rolesIds,
        ?int $statusId
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param string|null $name
     * @param string|null $email
     * @param array|null $rolesIds
     * @param int|null $statusId
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?string $name,
        ?string $email,
        ?array $rolesIds,
        ?int $statusId,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param array $adminsIds
     *
     * @return Collection
     */
    public function getByIds(
        array $adminsIds
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param int $authId
     * @param AdminStatusListItem $adminStatusListItem
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param bool $fullAccess
     *
     * @return Admin|null
     */
    public function store(
        int $authId,
        AdminStatusListItem $adminStatusListItem,
        string $firstName,
        string $lastName,
        string $email,
        bool $fullAccess
    ) : ?Admin;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Admin $admin
     * @param int|null $authId
     * @param AdminStatusListItem|null $adminStatusListItem
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $email
     * @param bool|null $fullAccess
     *
     * @return Admin
     */
    public function update(
        Admin $admin,
        ?int $authId,
        ?AdminStatusListItem $adminStatusListItem,
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?bool $fullAccess
    ) : Admin;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Admin $admin
     * @param AdminStatusListItem $adminStatusListItem
     *
     * @return Admin
     */
    public function updateStatus(
        Admin $admin,
        AdminStatusListItem $adminStatusListItem
    ) : Admin;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Admin $admin
     * @param bool $initialPassword
     *
     * @return Admin
     */
    public function updateInitialPassword(
        Admin $admin,
        bool $initialPassword
    ) : Admin;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Admin $admin
     *
     * @return Admin
     */
    public function setPasswordResetToken(
        Admin $admin
    ) : Admin;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Admin $admin
     *
     * @return Admin
     */
    public function dropPasswordResetToken(
        Admin $admin
    ) : Admin;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Admin $admin
     *
     * @return bool
     */
    public function delete(
        Admin $admin
    ) : bool;
}
