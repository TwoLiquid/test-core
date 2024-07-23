<?php

namespace App\Permissions\Department\Order;

use App\Permissions\Department\BaseDepartment;
use App\Permissions\Department\Order\Interfaces\OrderDepartmentInterface;

/**
 * Class OrderDepartment
 *
 * @package App\Permissions\Department\Order
 */
class OrderDepartment extends BaseDepartment implements OrderDepartmentInterface
{
    /**
     * Department name
     *
     * @var string
     */
    protected string $name = 'Orders';

    /**
     * Department code
     *
     * @var string
     */
    protected string $code = 'orders';

    /**
     * Department pages
     *
     * @var array
     */
    protected array $structure = [
        [
            'code'        => 'order_overview_list',
            'name'        => 'All vybes list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'  => 'order_overview_page',
            'name'  => 'Order overview page',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'order_overview_page.summary',
                    'name'        => 'Summary',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'order_overview_page.add_payment',
                    'name'        => 'Add payment',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'order_overview_page.options',
                    'name'        => 'Options',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'order_overview_page.credit',
                    'name'        => 'Credit',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'order_overview_page.refund',
                    'name'        => 'Refund',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'order_overview_page.invoices',
                    'name'        => 'Invoices',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ],
                [
                    'code'        => 'order_overview_page.tipping',
                    'name'        => 'Tipping',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'sale_overview_list',
            'name'        => 'Sale overview list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'  => 'sale_overview_page',
            'name'  => 'Sale overview page',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'sale_overview_page.summary',
                    'name'        => 'Summary',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'sale_overview_page.tipping',
                    'name'        => 'Tipping',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'order_items_list',
            'name'        => 'Order_items_list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'tips_list',
            'name'        => 'Tips list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'reserved_requests_list',
            'name'        => 'Reserved requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'pending_reschedule_requests_list',
            'name'        => 'Pending (reschedule) requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'in_process_requests_list',
            'name'        => 'In process requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'finish_requests_list',
            'name'        => 'Finish requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'in_process_reschedule_requests_list',
            'name'        => 'In process reschedule requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'finish_requests_list',
            'name'        => 'Finish requests list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'        => 'custom_orders_list',
            'name'        => 'Custom orders list',
            'type'        => 'page',
            'permissions' => [
                'view'
            ]
        ],
        [
            'code'  => 'custom_orders_page',
            'name'  => 'Custom orders page',
            'type'  => 'category',
            'pages' => [
                [
                    'code'        => 'custom_orders_page.summary',
                    'name'        => 'Summary',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'custom_orders_page.add_payment',
                    'name'        => 'Add payment',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'custom_orders_page.options',
                    'name'        => 'Options',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'custom_orders_page.credit',
                    'name'        => 'Credit',
                    'type'        => 'page',
                    'permissions' => [
                        'view',
                        'edit'
                    ]
                ],
                [
                    'code'        => 'custom_orders_page.invoices',
                    'name'        => 'Invoices',
                    'type'        => 'page',
                    'permissions' => [
                        'view'
                    ]
                ]
            ]
        ],
        [
            'code'        => 'add_new_order',
            'name'        => 'Add new order',
            'type'        => 'page',
            'permissions' => [
                'add'
            ]
        ]
    ];
}