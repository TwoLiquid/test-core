<?php

namespace App\Permissions\Department\Aggregator;

use App\Permissions\Department\Chat\ChatDepartment;
use App\Permissions\Department\Csau\CsauDepartment;
use App\Permissions\Department\Dashboard\DashboardDepartment;
use App\Permissions\Department\Finance\AffiliateDepartment as AffiliateFinanceDepartment;
use App\Permissions\Department\Finance\BuyerDepartment;
use App\Permissions\Department\Finance\PayoutMethodDepartment;
use App\Permissions\Department\Finance\SellerDepartment;
use App\Permissions\Department\General\GeneralDepartment;
use App\Permissions\Department\Invoice\InvoiceListDepartment;
use App\Permissions\Department\Invoice\InvoicePageDepartment;
use App\Permissions\Department\Notification\NotificationDepartment;
use App\Permissions\Department\Order\OrderDepartment;
use App\Permissions\Department\Report\AffiliateDepartment;
use App\Permissions\Department\Report\BillingDepartment;
use App\Permissions\Department\Report\ClientDepartment;
use App\Permissions\Department\Report\IncomeDepartment;
use App\Permissions\Department\Request\FinanceRequestDepartment;
use App\Permissions\Department\Request\UserRequestDepartment;
use App\Permissions\Department\Request\VybeRequestDepartment;
use App\Permissions\Department\Review\ReviewDepartment;
use App\Permissions\Department\Support\SupportDepartment;
use App\Permissions\Department\User\UserListDepartment;
use App\Permissions\Department\User\UserPageDepartment;
use App\Permissions\Department\Vybe\VybeDepartment;

/**
 * Class AdminDepartmentAggregator
 *
 * @package App\Permissions\Department\Aggregator
 */
class AdminDepartmentAggregator extends BaseDepartmentAggregator
{
    /**
     * Admin aggregator structure
     *
     * @var array
     */
    protected array $structure = [
        'Dashboard' => [
            DashboardDepartment::class
        ],
        'Users' => [
            UserListDepartment::class,
            UserPageDepartment::class
        ],
        'Finance (user page)' => [
            BuyerDepartment::class,
            AffiliateFinanceDepartment::class,
            SellerDepartment::class,
            PayoutMethodDepartment::class
        ],
        'Vybes' => [
            VybeDepartment::class
        ],
        'Requests' => [
            UserRequestDepartment::class,
            VybeRequestDepartment::class,
            FinanceRequestDepartment::class
        ],
        'CSAU' => [
            CsauDepartment::class
        ],
        'Order' => [
            OrderDepartment::class
        ],
        'Invoices' => [
            InvoiceListDepartment::class,
            InvoicePageDepartment::class
        ],
        'Reports' => [
            BillingDepartment::class,
            IncomeDepartment::class,
            ClientDepartment::class,
            AffiliateDepartment::class
        ],
        'Reviews' => [
            ReviewDepartment::class
        ],
        'Notifications' => [
            NotificationDepartment::class
        ],
        'Chats' => [
            ChatDepartment::class
        ],
        'Support' => [
            SupportDepartment::class
        ],
        'General' => [
            GeneralDepartment::class
        ]
    ];
}