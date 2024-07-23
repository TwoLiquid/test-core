<?php

namespace App\Permissions\Department;

use App\Lists\Permission\PermissionList;
use App\Models\MySql\Role;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BaseDepartment
 *
 * @package App\Permissions\Department
 */
class BaseDepartment
{
    /**
     * Role entity
     *
     * @var Role|null
     */
    protected ?Role $role;

    /**
     * Department name
     *
     * @var string
     */
    protected string $name;

    /**
     * Department code
     *
     * @var string
     */
    protected string $code;

    /**
     * Department structure
     *
     * @var array
     */
    protected array $structure;

    /**
     * @var PermissionRepository
     */
    protected PermissionRepository $permissionRepository;

    /**
     * BaseDepartment constructor
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
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getStructure() : array
    {
        /**
         * @var int $itemKey
         * @var array $item
         */
        foreach ($this->structure as $itemKey => $item) {
            if ($item['type'] == 'page') {
                $permissions = new Collection();

                /** @var string $permission */
                foreach ($this->structure[$itemKey]['permissions'] as $permission) {
                    $permissions->push(
                        PermissionList::getItemByCode(
                            $permission
                        )
                    );
                }

                $this->structure[$itemKey]['permissions'] = $permissions;
            } elseif ($item['type'] == 'category') {

                /** @var string $page */
                foreach ($this->structure[$itemKey]['pages'] as $pageKey => $page) {
                    $permissions = new Collection();

                    /** @var string $permission */
                    foreach ($this->structure[$itemKey]['pages'][$pageKey]['permissions'] as $permission) {
                        $permissions->push(
                            PermissionList::getItemByCode(
                                $permission
                            )
                        );
                    }

                    $this->structure[$itemKey]['pages'][$pageKey]['permissions'] = $permissions;
                }
            }
        }

        return $this->structure;
    }
}