<?php

namespace App\Permissions\Department\Finance;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Finance\Interfaces\AffiliateDepartmentInterface;

/**
 * Class AffiliateDepartment
 *
 * @package App\Permissions\Department\Finance
 */
class AffiliateDepartment extends BaseDepartment implements AffiliateDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Affiliate';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'affiliate';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'add_withdrawal_request',
            'name'        => 'Add withdrawal request',
            'type'        => 'page',
            'permissions' => [
                'add'
            ]
        ],
        [
            'code'  => 'withdrawals',
            'name'  => 'Withdrawals',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'withdrawals.requests',
                    'name'        => 'Requests',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'withdrawals.receipts',
                    'name'        => 'Receipts',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ]
            ]
        ],
        [
            'code'  => 'commissions',
            'name'  => 'Commissions',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'commissions.all_commissions',
                    'name'        => 'All commissions',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit',
                        'delete'
                    ]
                ],
                [
                    'code'        => 'commissions.pending_commissions',
                    'name'        => 'Pending commissions',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit',
                        'delete'
                    ]
                ],
                [
                    'code'        => 'commissions.commissions_history',
                    'name'        => 'Commissions history',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit',
                        'delete'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'transactions',
            'name'        => 'Transactions',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'add_manual_commission_entry',
            'name'        => 'Add Manual Commission Entry',
            'type'        => 'page',
            'permissions' => [
                'add'
            ]
        ],
        [
            'code'        => 'affiliate_statement',
            'name'        => 'Affiliate statement',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ]
    ];
}