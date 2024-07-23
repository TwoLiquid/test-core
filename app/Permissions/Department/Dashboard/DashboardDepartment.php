<?php

namespace App\Permissions\Department\Dashboard;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Dashboard\Interfaces\DashboardDepartmentInterface;

/**
 * Class DashboardDepartment
 *
 * @package App\Permissions\Department\Dashboard
 */
class DashboardDepartment extends BaseDepartment implements DashboardDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Dashboard';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'dashboard';

    /**
     * Department structure
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'dashboard',
            'name'        => 'Dashboard',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ]
    ];
}