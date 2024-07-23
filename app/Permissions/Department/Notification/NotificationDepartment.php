<?php

namespace App\Permissions\Department\Notification;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Notification\Interfaces\NotificationDepartmentInterface;

/**
 * Class NotificationDepartment
 *
 * @package App\Permissions\Department\Notification
 */
class NotificationDepartment extends BaseDepartment implements NotificationDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Notifications';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'notifications';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'email_notifications',
            'name'        => 'Email notifications',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
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
            'code'        => 'all_notifications',
            'name'        => 'All notifications',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'platform_notifications',
            'name'        => 'Platform notifications',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ]
    ];
}