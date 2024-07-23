<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Admin\AdminAuthProtection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface AdminAuthProtectionRepositoryInterface
 *
 * @package App\Repositories\Admin\Interfaces
 */
interface AdminAuthProtectionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return AdminAuthProtection|null
     */
    public function findById(
        ?int $id
    ) : ?AdminAuthProtection;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Admin $admin
     *
     * @return AdminAuthProtection|null
     */
    public function findByAdmin(
        Admin $admin
    ) : ?AdminAuthProtection;

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
     * @param Admin $admin
     * @param string $secret
     *
     * @return AdminAuthProtection|null
     */
    public function store(
        Admin $admin,
        string $secret
    ) : ?AdminAuthProtection;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param AdminAuthProtection $adminAuthProtection
     * @param Admin|null $admin
     * @param string|null $secret
     *
     * @return AdminAuthProtection
     */
    public function update(
        AdminAuthProtection $adminAuthProtection,
        ?Admin $admin,
        ?string $secret
    ) : AdminAuthProtection;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Admin $admin
     *
     * @return bool
     */
    public function deleteForAdmin(
        Admin $admin
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param AdminAuthProtection $adminAuthProtection
     *
     * @return bool
     */
    public function delete(
        AdminAuthProtection $adminAuthProtection
    ) : bool;
}
