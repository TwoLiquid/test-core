<?php

namespace App\Services\Permission\Interfaces;

use App\Models\MySql\Role;

/**
 * Interface PermissionServiceInterface
 *
 * @package App\Services\Permission\Interfaces
 */
interface PermissionServiceInterface
{
    /**
     * This method provides storing data
     *
     * @param Role $role
     * @param array $departments
     *
     * @return bool
     */
    public function createFromData(
        Role $role,
        array $departments
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param Role $role
     * @param array $departments
     */
    public function updateFromData(
        Role $role,
        array $departments
    ) : void;
}