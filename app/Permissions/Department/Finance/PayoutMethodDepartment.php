<?php

namespace App\Permissions\Department\Finance;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Finance\Interfaces\PayoutMethodDepartmentInterface;

/**
 * Class PayoutMethodDepartment
 *
 * @package App\Permissions\Department\Finance
 */
class PayoutMethodDepartment extends BaseDepartment implements PayoutMethodDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Payout methods';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'payout_methods';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'payout_methods_request',
            'name'        => 'Payout methods request',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'payout_methods',
            'name'        => 'Payout methods',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit',
                'add'
            ]
        ]
    ];
}