<?php

namespace App\Permissions\Department\General;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\General\Interfaces\GeneralDepartmentInterface;

/**
 * Class GeneralDepartment
 *
 * @package App\Permissions\Department\General
 */
class GeneralDepartment extends BaseDepartment implements GeneralDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'General';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'general';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'administrator_accounts',
            'name'        => 'Administrator accounts',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'administrator_roles',
            'name'        => 'Administrator roles',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'general_settings',
            'name'        => 'General settings',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'user_settings',
            'name'        => 'User settings',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add'
            ]
        ],
        [
            'code'        => 'vybe_settings',
            'name'        => 'Vybe settings',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'request_settings',
            'name'        => 'Request settings',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'suspicious_words',
            'name'        => 'Suspicious words',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'registration_ip_database',
            'name'        => 'Registration IP database',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'payments_and_withdrawals',
            'name'        => 'Payments & withdrawals',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'tax_rules',
            'name'        => 'Tax rules',
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