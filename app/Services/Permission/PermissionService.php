<?php

namespace App\Services\Permission;

use App\Exceptions\DatabaseException;
use App\Lists\Permission\PermissionList;
use App\Models\MySql\Role;
use App\Repositories\Permission\PermissionRepository;
use App\Services\Permission\Interfaces\PermissionServiceInterface;

/**
 * Class PermissionService
 *
 * @package App\Services\Permission
 */
class PermissionService implements PermissionServiceInterface
{
    /**
     * @var PermissionRepository
     */
    protected PermissionRepository $permissionRepository;

    /**
     * PermissionService constructor
     */
    public function __construct()
    {
        /** @var PermissionRepository permissionRepository */
        $this->permissionRepository = new PermissionRepository();
    }

    /**
     * @param Role $role
     * @param array $departments
     *
     * @return array
     */
    private function getPreparedData(
        Role $role,
        array $departments
    ) : array
    {
        $data = [];

        /** @var array $department */
        foreach ($departments as $department) {

            /** @var array $permission */
            foreach ($department['permissions'] as $permission) {
                $data[] = [
                    'role_id'         => $role->id,
                    'permission_id'   => $permission['permission_id'],
                    'department_code' => $department['department_code'],
                    'page_code'       => $department['page_code'],
                    'selected'        => $permission['selected']
                ];
            }
        }

        return $data;
    }

    /**
     * @param Role $role
     * @param array $departments
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function createFromData(
        Role $role,
        array $departments
    ) : bool
    {
        /**
         * Returning insert or an update result
         */
        return $this->permissionRepository->storeMany(
            $this->getPreparedData(
                $role,
                $departments
            )
        );
    }

    /**
     * @param Role $role
     * @param array $departments
     *
     * @throws DatabaseException
     */
    public function updateFromData(
        Role $role,
        array $departments
    ) : void
    {
        /**
         * Getting prepared data
         */
        $preparedData = $this->getPreparedData(
            $role,
            $departments
        );

        /** @var array $preparedDataItem */
        foreach ($preparedData as $preparedDataItem) {

            /**
             * Getting permission
             */
            $permissionListItem = PermissionList::getItem(
                $preparedDataItem['permission_id']
            );

            /**
             * Updating permission
             */
            $this->permissionRepository->updateFromParameters(
                $role,
                $permissionListItem,
                $preparedDataItem['department_code'],
                $preparedDataItem['page_code'],
                $preparedDataItem['selected']
            );
        }
    }
}
