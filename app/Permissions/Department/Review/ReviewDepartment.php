<?php

namespace App\Permissions\Department\Review;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Review\Interfaces\ReviewDepartmentInterface;

/**
 * Class ReviewDepartment
 *
 * @package App\Permissions\Department\Review
 */
class ReviewDepartment extends BaseDepartment implements ReviewDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Reviews';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'reviews';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'all_reviews',
            'name'        => 'All reviews',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'delete'
            ]
        ],
        [
            'code'        => 'pending_reviews',
            'name'        => 'Pending reviews',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'delete'
            ]
        ],
        [
            'code'        => 'reported_reviews',
            'name'        => 'Reported reviews',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'delete'
            ]
        ],
        [
            'code'        => 'accepted_reviews',
            'name'        => 'Accepted reviews',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'delete'
            ]
        ],
        [
            'code'        => 'declined_reviews',
            'name'        => 'Declined reviews',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'delete'
            ]
        ]
    ];
}