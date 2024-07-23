<?php

namespace App\Repositories\Permission\Interfaces;

use App\Lists\Permission\PermissionListItem;
use App\Models\MySql\Permission;
use App\Models\MySql\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface PermissionRepositoryInterface
 *
 * @package App\Repositories\Permission\Interfaces
 */
interface PermissionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Permission|null
     */
    public function findById(
        ?int $id
    ) : ?Permission;

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
     * with an eloquent model by certain query
     *
     * @param Role $role
     *
     * @return Collection
     */
    public function getAllForRole(
        Role $role
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Role $role
     * @param PermissionListItem $permissionListItem
     * @param string $departmentCode
     * @param string $pageCode
     * @param bool $selected
     *
     * @return Permission|null
     */
    public function store(
        Role $role,
        PermissionListItem $permissionListItem,
        string $departmentCode,
        string $pageCode,
        bool $selected
    ) : ?Permission;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Permission $permission
     * @param Role|null $role
     * @param PermissionListItem|null $permissionListItem
     * @param string|null $departmentCode
     * @param string|null $pageCode
     * @param bool|null $selected
     *
     * @return Permission
     */
    public function update(
        Permission $permission,
        ?Role $role,
        ?PermissionListItem $permissionListItem,
        ?string $departmentCode,
        ?string $pageCode,
        ?bool $selected
    ) : Permission;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param Role $role
     * @param PermissionListItem $permissionListItem
     * @param string $departmentCode
     * @param string $pageCode
     * @param bool $selected
     *
     * @return bool
     */
    public function updateFromParameters(
        Role $role,
        PermissionListItem $permissionListItem,
        string $departmentCode,
        string $pageCode,
        bool $selected
    ) : bool;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param array $data
     *
     * @return bool
     */
    public function storeMany(
        array $data
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Permission $permission
     *
     * @return bool
     */
    public function delete(
        Permission $permission
    ) : bool;
}
