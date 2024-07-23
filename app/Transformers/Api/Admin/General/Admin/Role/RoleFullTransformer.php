<?php

namespace App\Transformers\Api\Admin\General\Admin\Role;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Role;
use App\Permissions\Department\Aggregator\AdminDepartmentAggregator;
use App\Transformers\BaseTransformer;

/**
 * Class RoleFullTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin\Role
 */
class RoleFullTransformer extends BaseTransformer
{
    /**
     * @var Role
     */
    protected Role $role;

    /**
     * @var AdminDepartmentAggregator
     */
    protected AdminDepartmentAggregator $adminDepartmentAggregator;

    /**
     * RoleFullTransformer constructor
     *
     * @param Role $role
     */
    public function __construct(
        Role $role
    )
    {
        /** @var Role role */
        $this->role = $role;

        /** @var AdminDepartmentAggregator adminDepartmentAggregator */
        $this->adminDepartmentAggregator = new AdminDepartmentAggregator($role);
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function transform() : array
    {
        return $this->adminDepartmentAggregator
            ->getStructure();
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
