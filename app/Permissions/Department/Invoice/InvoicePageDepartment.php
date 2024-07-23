<?php

namespace App\Permissions\Department\Invoice;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Invoice\Interfaces\InvoicePageDepartmentInterface;

/**
 * Class InvoicePageDepartment
 *
 * @package App\Permissions\Department\Invoice
 */
class InvoicePageDepartment extends BaseDepartment implements InvoicePageDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Invoices page';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'invoices_page';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'  => 'payment_receipts_page',
            'name'  => 'Payment receipts page',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'payment_receipts_page.summary',
                    'name'        => 'Summary',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'payment_receipts_page.add_payment',
                    'name'        => 'Add payment',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'payment_receipts_page.options',
                    'name'        => 'Options',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'payment_receipts_page.credit',
                    'name'        => 'Credit',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'payment_receipts_page.invoices',
                    'name'        => 'Invoices',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'sale_receipt_page',
            'name'        => 'Sale receipt page',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'  => 'add_funds_receipts_page',
            'name'  => 'Add funds receipts page',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'add_funds_receipts_page.summary',
                    'name'        => 'Summary',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'add_funds_receipts_page.add_payment',
                    'name'        => 'Add payment',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'add_funds_receipts_page.options',
                    'name'        => 'Options',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'add_funds_receipts_page.refund',
                    'name'        => 'Refund',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'add_funds_receipts_page.notes',
                    'name'        => 'Notes',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ]
            ]
        ],
        [
            'code'  => 'withdrawal_receipts_page',
            'name'  => 'Withdrawal receipts page',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'withdrawal_receipts_page.withdrawal_receipt',
                    'name'        => 'Withdrawal receipt',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'withdrawal_receipts_page.credit_withdrawal_receipt',
                    'name'        => 'Credit withdrawal receipt',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'withdrawal_receipts_page.add_transfer',
                    'name'        => 'Add transfer',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'withdrawal_receipts_page.notes',
                    'name'        => 'Notes',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'tax_invoices_page',
            'name'        => 'Tax invoices page',
            'type'        => 'page',
            'permissions' => [
                'view',
                'edit'
            ]
        ],
        [
            'code'  => 'custom_invoices_page',
            'name'  => 'Custom invoices page',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'custom_invoices_page.summary',
                    'name'        => 'Summary',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'custom_invoices_page.add_payment',
                    'name'        => 'Add payment',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'custom_invoices_page.options',
                    'name'        => 'Options',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'custom_invoices_page.refund',
                    'name'        => 'Refund',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ]
            ]
        ]
    ];
}