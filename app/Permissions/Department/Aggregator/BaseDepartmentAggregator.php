<?php

namespace App\Permissions\Department\Aggregator;

use App\Exceptions\DatabaseException;
use App\Lists\Permission\PermissionListItem;
use App\Models\MySql\Permission;
use App\Models\MySql\Role;
use App\Permissions\Department\BaseDepartment;
use App\Repositories\Permission\PermissionRepository;

/**
 * Class BaseDepartmentAggregator
 *
 * @package App\Permissions\Department\Aggregator
 */
class BaseDepartmentAggregator
{
    /**
     * Department aggregator structure
     *
     * @var array
     */
    protected array $structure;

    /**
     * Department aggregator role
     *
     * @var Role|null
     */
    protected ?Role $role;

    /**
     * @var PermissionRepository
     */
    protected PermissionRepository $permissionRepository;

    /**
     * BaseDepartmentAggregator constructor
     *
     * @param Role|null $role
     */
    public function __construct(
        ?Role $role = null
    )
    {
        /** @var Role role */
        $this->role = $role;

        /** @var PermissionRepository permissionRepository */
        $this->permissionRepository = new PermissionRepository();
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function getStructure() : array
    {
        $structure = [];

        /**
         * @var string $name
         * @var array $item
         */
        foreach ($this->structure as $name => $sections) {
            $departments = [];

            /** @var BaseDepartment $section */
            foreach ($sections as $section) {
                $department = new $section();

                $departments[] = [
                    'code'      => $department->getCode(),
                    'name'      => $department->getName(),
                    'structure' => $department->getStructure()
                ];
            }

            $structure[] = [
                'name'        => $name,
                'departments' => $departments
            ];
        }

        if ($this->role) {
            return $this->broach(
                $structure
            );
        }

        return $structure;
    }

    /**
     * @param array $structure
     *
     * @return array
     *
     * @throws DatabaseException
     */
    private function broach(
        array $structure
    ) : array
    {
        $permissions = $this->permissionRepository->getAllForRole(
            $this->role
        );

        /** @var array $section */
        foreach ($structure as $section) {

            /** @var array $department */
            foreach ($section['departments'] as $department) {

                /** @var array $item */
                foreach ($department['structure'] as $item) {
                    if ($item['type'] == 'page') {

                        /** @var PermissionListItem $permissionListItem */
                        foreach ($item['permissions'] as $permissionListItem) {

                            /** @var Permission $permission */
                            $permission = $permissions->where('permission_id', '=', $permissionListItem->id)
                                ->where('department_code', '=', $department['code'])
                                ->where('page_code', '=', $item['code'])
                                ->first();

                            if ($permission) {
                                $permissionListItem->setSelected(
                                    $permission->selected
                                );
                            }
                        }
                    } elseif ($item['type'] == 'category') {

                        /** @var array $page */
                        foreach ($item['pages'] as $page) {

                            /** @var PermissionListItem $permissionListItem */
                            foreach ($page['permissions'] as $permissionListItem) {

                                /** @var Permission $permission */
                                $permission = $permissions->where('permission_id', '=', $permissionListItem->id)
                                    ->where('department_code', '=', $department['code'])
                                    ->where('page_code', '=', $page['code'])
                                    ->first();

                                if ($permission) {
                                    $permissionListItem->setSelected(
                                        $permission->selected
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        return $structure;
    }
}