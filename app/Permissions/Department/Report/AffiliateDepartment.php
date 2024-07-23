<?php

namespace App\Permissions\Department\Report;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Report\Interfaces\AffiliateDepartmentInterface;

/**
 * Class AffiliateDepartment
 *
 * @package App\Permissions\Department\Report
 */
class AffiliateDepartment extends BaseDepartment implements AffiliateDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Affiliates';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'affiliates';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'affiliates_overview',
            'name'        => 'Affiliates Overview',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ]
    ];
}