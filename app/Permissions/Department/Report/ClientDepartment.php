<?php

namespace App\Permissions\Department\Report;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Report\Interfaces\ClientDepartmentInterface;

/**
 * Class ClientDepartment
 *
 * @package App\Permissions\Department\Report
 */
class ClientDepartment extends BaseDepartment implements ClientDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Clients';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'clients';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'credit_history_on_date',
            'name'        => 'Credit History on Date',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'  => 'credits_reviewer',
            'name'  => 'Credits Reviewer',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'credits_reviewer.buyer',
                    'name'        => 'Buyer',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'credits_reviewer.seller',
                    'name'        => 'Seller',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'credits_reviewer.affiliate',
                    'name'        => 'Affiliate',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ]
            ]
        ],
        [
            'code'  => 'client_account_register_balance',
            'name'  => 'Client account register balance',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'client_account_register_balance.buyer',
                    'name'        => 'Buyer',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'client_account_register_balance.seller',
                    'name'        => 'Seller',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'client_account_register_balance.affiliate',
                    'name'        => 'Affiliate',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'new_customers',
            'name'        => 'New Customers',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ]
    ];
}