<?php

namespace App\Permissions\Department\Chat;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Chat\Interfaces\ChatDepartmentInterface;

/**
 * Class ChatDepartment
 *
 * @package App\Permissions\Department\Chat
 */
class ChatDepartment extends BaseDepartment implements ChatDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Chats';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'chats';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'all_chats',
            'name'        => 'All chats',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'good_chats',
            'name'        => 'Good chats',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'suspicious_chats',
            'name'        => 'Suspicious chats',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'reported_chats',
            'name'        => 'Reported chats',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ]
    ];
}