<?php

namespace App\Permissions\Department\Request;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Request\Interfaces\FinanceRequestDepartmentInterface;

/**
 * Class FinanceRequestDepartment
 *
 * @package App\Permissions\Department\Request
 */
class FinanceRequestDepartment extends BaseDepartment implements FinanceRequestDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Finance requests';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'finance_requests';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'billing_change_requests_list',
            'name'        => 'Billing change requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'billing_information_billing_change_request',
            'name'        => 'Billing information (billing change request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'withdrawal_requests_list',
            'name'        => 'Withdrawal requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'finance_withdrawal_request',
            'name'        => 'Finance (withdrawal request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'payout_method_requests_list',
            'name'        => 'Payout method requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'finance_payout_method_request',
            'name'        => 'Finance (payout method request)',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ]
    ];
}