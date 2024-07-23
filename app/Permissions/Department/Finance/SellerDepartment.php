<?php

namespace App\Permissions\Department\Finance;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Finance\Interfaces\SellerDepartmentInterface;

/**
 * Class SellerDepartment
 * 
 * @package App\Permissions\Department\Finance
 */
class SellerDepartment extends BaseDepartment implements SellerDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Seller';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'seller';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'create_vybe',
            'name'        => 'Create vybe',
            'type'        => 'page',
            'permissions' => [
                'add'
            ]
        ],
        [
            'code'        => 'add_withdrawal_request',
            'name'        => 'Add withdrawal request',
            'type'        => 'page',
            'permissions' => [
                'add'
            ]
        ],
        [
            'code'        => 'assign_to_affiliate',
            'name'        => 'Assign to affiliate',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'  => 'seller_documents',
            'name'  => 'Seller documents',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'seller_documents.sale_overviews',
                    'name'        => 'Sale overviews',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'seller_documents.list_of_order_items',
                    'name'        => 'List of order items',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'seller_documents.sale_receipts',
                    'name'        => 'Sale receipts',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'seller_documents.withdrawals_receipts',
                    'name'        => 'Withdrawals receipts',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'seller_documents.sale_reports',
                    'name'        => 'Sale Reports',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'withdrawal_requests',
            'name'        => 'Withdrawal requests',
            'type'        => 'page',
            'permissions' => [
                'edit'
            ]
        ],
        [
            'code'        => 'view_account_statement',
            'name'        => 'View account statement',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'        => 'transactions',
            'name'        => 'Transactions',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'  => 'credit_management',
            'name'  => 'Credit management',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'credit_management.transfer_credit',
                    'name'        => 'Transfer credit',
                    'type'        => 'page',
                    'permissions' => [
                        'edit'
                    ]
                ],
                [
                    'code'        => 'credit_management.remove_credit',
                    'name'        => 'Remove credit',
                    'type'        => 'page',
                    'permissions' => [
                        'edit'
                    ]
                ]
            ]
        ]
    ];
}