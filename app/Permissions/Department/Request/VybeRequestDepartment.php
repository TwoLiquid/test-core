<?php

namespace App\Permissions\Department\Request;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Request\Interfaces\VybeRequestDepartmentInterface;

/**
 * Class VybeRequestDepartment
 *
 * @package App\Permissions\Department\Request
 */
class VybeRequestDepartment extends BaseDepartment implements VybeRequestDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Vybe requests';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'vybe_requests';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'vybe_change_requests_list',
            'name'        => 'Vybe change requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'vybe_page_vybe_change_request',
            'name'        => 'Vybe page (vybe change request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'vybe_publish_requests_list',
            'name'        => 'Vybe publish requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'vybe_page_vybe_publish_request',
            'name'        => 'Vybe page (vybe publish request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'vybe_unpublish_requests_list',
            'name'        => 'Vybe unpublish requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'vybe_page_vybe_unpublish_request',
            'name'        => 'Vybe page (vybe unpublish request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'vybe_unsuspension_requests_list',
            'name'        => 'Vybe unsuspension requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'vybe_page_vybe_unsuspension_request',
            'name'        => 'Vybe page (vybe unsuspension request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'vybe_deletion_requests_list',
            'name'        => 'Vybe deletion requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'vybe_page_vybe_deletion_request',
            'name'        => 'Vybe page (vybe deletion request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ]
    ];
}