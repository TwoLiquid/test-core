<?php

namespace App\Permissions\Department\Vybe;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Vybe\Interfaces\VybeDepartmentInterface;

/**
 * Class VybeDepartment
 *
 * @package App\Permissions\Department\Vybe
 */
class VybeDepartment extends BaseDepartment implements VybeDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Vybes';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'vybes';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'all_vybes_list',
            'name'        => 'All vybes list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'vybe_page',
            'name'        => 'Vybe page',
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