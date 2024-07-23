<?php

namespace App\Permissions\Department\Request;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Request\Interfaces\UserRequestDepartmentInterface;

/**
 * Class UserRequestDepartment
 *
 * @package App\Permissions\Department\Request
 */
class UserRequestDepartment extends BaseDepartment implements UserRequestDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'User requests';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'user_requests';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'new_user_requests_list',
            'name'        => 'New user requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'profile_information_new_user_request',
            'name'        => 'Profile information (new user request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'user_id_verification_requests_list',
            'name'        => 'User ID verification requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'user_id_verification_id_verification_request',
            'name'        => 'User ID verification (ID verification request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'user_deactivation_requests_list',
            'name'        => 'User deactivation requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'profile_information_deactivation_request',
            'name'        => 'Profile information (deactivation request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'user_unsuspension_requests_list',
            'name'        => 'User unsuspension requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'profile_information_unsuspension_request',
            'name'        => 'Profile information (unsuspension request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'user_deletion_requests_list',
            'name'        => 'User deletion requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'profile_information_deletion_request',
            'name'        => 'Profile information (deletion request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ]
    ];
}