<?php

namespace App\Permissions\Department\Finance;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Finance\Interfaces\BuyerDepartmentInterface;

/**
 * Class BuyerDepartment
 *
 * @package App\Permissions\Department\Finance
 */
class BuyerDepartment extends BaseDepartment implements BuyerDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Buyer';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'buyer';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'add_funds_request',
            'name'        => 'Add funds request',
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
            'code'  => 'buyer_documents',
            'name'  => 'Buyer documents',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'buyer_documents.order_overviews',
                    'name'        => 'Order overviews',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'buyer_documents.list_of_order_items',
                    'name'        => 'List of order items',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'buyer_documents.payment_receipts',
                    'name'        => 'Payment receipts',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'buyer_documents.tax_invoices',
                    'name'        => 'Tax invoices',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'buyer_documents.add_funds',
                    'name'        => 'Add funds',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ]
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
                    'code'        => 'credit_management.add_credit',
                    'name'        => 'Add credit',
                    'type'        => 'page',
                    'permissions' => [
                        'add'
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