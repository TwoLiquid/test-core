<?php

namespace App\Permissions\Department\Invoice;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Invoice\Interfaces\InvoiceListDepartmentInterface;

/**
 * Class InvoiceListDepartment
 *
 * @package App\Permissions\Department\Invoice
 */
class InvoiceListDepartment extends BaseDepartment implements InvoiceListDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'List of invoices';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'list_of_invoices';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'payment_receipts_list',
            'name'        => 'Payment receipts list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'sale_receipts_list',
            'name'        => 'Sale receipts list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'add_funds_receipts_list',
            'name'        => 'Add funds receipts list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'withdrawal_receipts_list',
            'name'        => 'Withdrawal receipts list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'tax_invoices_list',
            'name'        => 'Tax invoices list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'custom_invoices_list',
            'name'        => 'Custom invoices list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
    ];
}