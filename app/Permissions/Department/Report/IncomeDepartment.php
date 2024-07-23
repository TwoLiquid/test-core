<?php

namespace App\Permissions\Department\Report;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Report\Interfaces\IncomeDepartmentInterface;

/**
 * Class IncomeDepartment
 *
 * @package App\Permissions\Department\Report
 */
class IncomeDepartment extends BaseDepartment implements IncomeDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Income';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'income';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'annual_income_report',
            'name'        => 'Annual Income Report',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'daily_performance',
            'name'        => 'Daily Performance',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'month_report',
            'name'        => 'Month Report',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ]
    ];
}