<?php

namespace App\Permissions\Department\Csau;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Csau\Interfaces\CsauDepartmentInterface;

/**
 * Class CsauDepartment
 *
 * @package App\Permissions\Department\Csau
 */
class CsauDepartment extends BaseDepartment implements CsauDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'CSAU';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'csau';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'csau',
            'name'        => 'CSAU',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'csau_suggestions',
            'name'        => 'CSAU (suggestions)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'activity_tags',
            'name'        => 'Activity tags',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'devices',
            'name'        => 'Devices',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'devices_suggestions',
            'name'        => 'Devices (suggestions)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'event_units',
            'name'        => 'Event units',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'event_units_suggestions',
            'name'        => 'Event units (suggestions)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'platforms',
            'name'        => 'Platforms',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'countries_regions_and_cities',
            'name'        => 'Countries, regions & cities',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add',
                'delete'
            ]
        ],
        [
            'code'        => 'countries_region_and_cities_suggestions',
            'name'        => 'Countries, regions & cities (suggestions)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ]
    ];
}