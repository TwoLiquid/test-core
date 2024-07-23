<?php

namespace App\Permissions\Department\User;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\User\Interfaces\UserListDepartmentInterface;

/**
 * Class UserListDepartment
 *
 * @package App\Permissions\Department\User
 */
class UserListDepartment extends BaseDepartment implements UserListDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'User list';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'user_list';

    /**
     * Department structure
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'buyers_list',
            'name'        => 'Buyers list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'sellers_list',
            'name'        => 'Sellers list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'affiliates_list',
            'name'        => 'Affiliates list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'users_with_custom_settings_list',
            'name'        => 'Users with custom settings list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ]
    ];
}