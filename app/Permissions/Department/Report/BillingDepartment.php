<?php

namespace App\Permissions\Department\Report;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Report\Interfaces\BillingDepartmentInterface;

/**
 * Class BillingDepartment
 *
 * @package App\Permissions\Department\Report
 */
class BillingDepartment extends BaseDepartment implements BillingDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Billing';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'billing';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'melding_éénloketsysteem',
            'name'        => 'Melding éénloketsysteem',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'tax_sales_report',
            'name'        => 'Tax Sales Report',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'tax_refund_report',
            'name'        => 'Tax Refund Report',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'sales_by_period',
            'name'        => 'Sales by Period',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ]
    ];
}