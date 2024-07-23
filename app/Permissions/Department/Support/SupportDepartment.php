<?php

namespace App\Permissions\Department\Support;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Support\Interfaces\SupportDepartmentInterface;

/**
 * Class SupportDepartment
 *
 * @package App\Permissions\Department\Support
 */
class SupportDepartment extends BaseDepartment implements SupportDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Support';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'support';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'all_tickets',
            'name'        => 'All tickets',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'support_tickets',
            'name'        => 'Support tickets',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'finance_tickets',
            'name'        => 'Finance tickets',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'report_tickets',
            'name'        => 'Report tickets',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'dispute_tickets',
            'name'        => 'Dispute tickets',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ]
    ];
}