<?php

namespace App\Transformers\Api\Admin\General\Admin\Role;

use App\Exceptions\DatabaseException;
use App\Permissions\Department\Aggregator\AdminDepartmentAggregator;
use App\Transformers\BaseTransformer;

/**
 * Class RoleFormTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin\Role
 */
class RoleFormTransformer extends BaseTransformer
{
    /**
     * @var AdminDepartmentAggregator
     */
    protected AdminDepartmentAggregator $adminDepartmentAggregator;

    /**
     * RoleFormTransformer constructor
     */
    public function __construct()
    {
        /** @var AdminDepartmentAggregator adminDepartmentAggregator */
        $this->adminDepartmentAggregator = new AdminDepartmentAggregator();
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
