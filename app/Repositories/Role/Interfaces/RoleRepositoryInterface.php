<?php

namespace App\Repositories\Role\Interfaces;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface RoleRepositoryInterface
 *
 * @package App\Repositories\Role\Interfaces
 */
interface RoleRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Role|null
     */
    public function findById(
        ?int $id
    ) : ?Role;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Role|null
     */
    public function findFullById(
        ?int $id
    ) : ?Role;

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
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param string $name
     *
     * @return Role|null
     */
    public function store(
        string $name
    ) : ?Role;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Role $role
     * @param string|null $name
     *
     * @return Role
     */
    public function update(
        Role $role,
        ?string $name
    ) : Role;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param Role $role
     * @param Admin $admin
     */
    public function attachAdmin(
        Role $role,
        Admin $admin
    ) : void;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param Role $role
     * @param array $adminsIds
     * @param bool|null $detaching
     */
    public function attachAdmins(
        Role $role,
        array $adminsIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param Role $role
     * @param Admin $admin
     */
    public function detachAdmin(
        Role $role,
        Admin $admin
    ) : void;

    /**
     * This method provides detaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param Role $role
     * @param array $adminsIds
     */
    public function detachAdmins(
        Role $role,
        array $adminsIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Role $role
     *
     * @return bool
     */
    public function delete(
        Role $role
    ) : bool;
}
