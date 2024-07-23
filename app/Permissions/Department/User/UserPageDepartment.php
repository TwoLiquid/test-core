<?php

namespace App\Permissions\Department\User;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\User\Interfaces\UserPageDepartmentInterface;

/**
 * Class UserPageDepartment
 *
 * @package App\Permissions\Department\User
 */
class UserPageDepartment extends BaseDepartment implements UserPageDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'User page';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'user_page';

    /**
     * Department structure
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'user_account',
            'name'        => 'User account',
            'type'        => 'page',
            'permissions' => [
                'delete'
            ]
        ],
        [
            'code'        => 'profile_information',
            'name'        => 'Profile information',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'billing_information',
            'name'        => 'Billing information',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'id_verification',
            'name'        => 'ID verification',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'finance',
            'name'        => 'Finance',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'vybes',
            'name'        => 'Vybes',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'settings',
            'name'        => 'Settings',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'statistics',
            'name'        => 'Statistics',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'other_information',
            'name'        => 'Other information',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'logs',
            'name'        => 'Logs',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'notes',
            'name'        => 'Notes',
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