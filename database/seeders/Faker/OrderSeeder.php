<?php

namespace Database\Seeders\Faker;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\LogException;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderTransactionRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Invoice\InvoiceService;
use App\Services\Log\LogService;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use App\Services\Sale\SaleService;
use App\Services\Timeslot\TimeslotService;
use App\Services\Vybe\VybeService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use JsonMapper_Exception;

/**
 * Class OrderSeeder
 *
 * @package Database\Seeders\Faker
 */
class OrderSeeder extends Seeder
{
    /**
     * @var string|null
     */
    protected ?string $amount;

    /**
     * Quantity of seeded orders
     */
    protected const ORDER_PER_USER_QUANTITY = [
        'min' => 5,
        'max' => 20
    ];

    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var InvoiceService
     */
    protected InvoiceService $invoiceService;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderTransactionRepository
     */
    protected OrderTransactionRepository $orderTransactionRepository;

    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * @var SaleService
     */
    protected SaleService $saleService;

    /**
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * OrderSeeder constructor
     *
     * @param string|null $amount
     */
    public function __construct(
        ?string $amount = null
    )
    {
        /** @var string amount */
        $this->amount = $amount;

        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var OrderRepository orderRepository */
        $this->orderRepository = new OrderRepository();

        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderTransactionRepository orderTransactionRepository */
        $this->orderTransactionRepository = new OrderTransactionRepository();

        /** @var OrderService orderService */
        $this->orderService = new OrderService();

        /** @var SaleService saleService */
        $this->saleService = new SaleService();

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function run() : void
    {
        /**
         * Preparing orders variable
         */
        $orders = new Collection();

        /** @var UserBalanceRepository $userBalanceRepository */
        $userBalanceRepository = app(UserBalanceRepository::class);

        /** @var OrderItemService $orderItemService */
        $orderItemService = app(OrderItemService::class);

        /** @var Collection $users */
        $users = app(UserRepository::class)->getAll();

        /** @var User $buyer */
        foreach ($users as $buyer) {
            for ($i = 0; $i < self::ORDER_PER_USER_QUANTITY[$this->amount ?: 'max']; $i++) {

                /** @var PaymentMethod $paymentMethod */
                $paymentMethod = PaymentMethod::find(1);

                /**
                 * Getting vybes for order items
                 */
                $vybes = Vybe::inRandomOrder()
                    ->where('user_id', '!=', $buyer->id)
                    ->whereIn('status_id', [
                        VybeStatusList::getPublishedItem()->id,
                        VybeStatusList::getPausedItem()->id
                    ])
                    ->limit(rand(1, 3))
                    ->get();

                /**
                 * Preparing order items an array
                 */
                $orderItems = [];

                /** @var Vybe $vybe */
                foreach ($vybes as $vybe) {

                    if ($vybe->getType()->isSolo()) {
                        $calendar = $this->vybeService->getSoloCalendarForOrder(
                            $vybe,
                            Carbon::now()
                        );
                    } elseif ($vybe->getType()->isGroup()) {
                        $calendar = $this->vybeService->getGroupCalendarForOrder(
                            $vybe,
                            Carbon::now()
                        );
                    } else {
                        $calendar = $this->vybeService->getEventCalendarForOrder(
                            $vybe
                        );
                    }

                    $availableCalendar = [];

                    /** @var array $calendarDay */
                    foreach ($calendar as $calendarDay) {
                        if (!empty($calendarDay['schedules'])) {
                            $availableCalendar[] = $calendarDay;
                        }
                    }

                    if (!empty($availableCalendar)) {
                        $availableDay = $availableCalendar[rand(0, count($availableCalendar) - 1)];
                        $randomDaySchedule = $availableDay['schedules'][rand(0, count($availableDay['schedules']) - 1)];
                        $appearanceCase = $vybe->appearanceCases
                            ->random(1)
                            ->first();

                        $isAvailable = $this->timeslotService->isAvailable(
                            $buyer,
                            $appearanceCase,
                            Carbon::parse($randomDaySchedule['datetime_from']),
                            Carbon::parse($randomDaySchedule['datetime_to']),
                            false
                        );

                        if ($isAvailable === true) {
                            $orderItems[] = [
                                'appearance_case_id' => $appearanceCase->id,
                                'datetime_from'      => Carbon::parse($randomDaySchedule['datetime_from'])->format('Y-m-d H:i:s'),
                                'datetime_to'        => Carbon::parse($randomDaySchedule['datetime_to'])->format('Y-m-d H:i:s'),
                                'quantity'           => rand(1, 2)
                            ];
                        }
                    }
                }

                if (!empty($orderItems)) {
                    $order = $this->orderService->createOrder(
                        $buyer,
                        $paymentMethod,
                        $orderItems
                    );

                    try {

                        /**
                         * Creating order overview log
                         */
                        $this->logService->addOrderOverviewLog(
                            $order,
                            $order->buyer->getBuyerBalance(),
                            UserBalanceTypeList::getBuyer(),
                            'created'
                        );

                    } catch (LogException) {
                        // Ignore exception via faker case uselessness
                    }

                    try {

                        /**
                         * Creating order invoice for buyer log
                         */
                        $this->logService->addInvoiceForBuyerLog(
                            $order->getBuyerInvoice(),
                            $order->buyer->getBuyerBalance(),
                            UserBalanceTypeList::getBuyer(),
                            'created'
                        );
                    } catch (LogException) {
                        // Ignore exception via faker case uselessness
                    }

                    $orders->push(
                        $order
                    );
                }
            }
        }

        /** @var Order $order */
        foreach ($orders as $order) {
            $order = $this->orderRepository->findFullById(
                $order->id
            );

            if ($order->getBuyerInvoice()) {
                $this->orderTransactionRepository->store(
                    $order->getBuyerInvoice(),
                    $order->method,
                    null,
                    $order->amount_total,
                    null,
                    null
                );
            }

            try {

                /**
                 * Creating sale overviews log
                 */
                $this->saleService->addTransactionLogs(
                    $order->sales,
                    'created'
                );
            } catch (LogException) {
                // Ignore exception via faker case uselessness
            }

            try {

                /**
                 * Creating order invoice for sellers log
                 */
                $this->invoiceService->addSellerTransactionLogs(
                    $order->invoices,
                    'created'
                );
            } catch (LogException) {
                // Ignore exception via faker case uselessness
            }

            try {

                /**
                 * Creating order invoice for sellers log
                 */
                $this->invoiceService->addSellerTransactionLogs(
                    $order->invoices,
                    'paid'
                );
            } catch (LogException) {
                // Ignore exception via faker case uselessness
            }

            $statuses = [
                1 => OrderItemStatusList::getCanceled()->code,
                2 => OrderItemStatusList::getReschedule()->code,
                3 => OrderItemStatusList::getFinished()->code,
                4 => OrderItemStatusList::getFinished()->code,
                5 => OrderItemStatusList::getFinished()->code,
                6 => OrderItemStatusList::getFinished()->code,
                7 => OrderItemStatusList::getDisputed()->code,
                8 => OrderItemStatusList::getFinishedDispute()->code,
                9 => OrderItemStatusList::getCanceledDispute()->code
            ];

            $result = $statuses[rand(1, 9)];

            /**
             * If order item is finished
             */
            if ($result == 'finished') {

                /**
                 * Executing payment by method
                 */
                $order = $this->orderService->executePayment(
                    $order
                );

                /**
                 * Updating all paid order documents
                 */
                $order = $this->orderService->updatePaidOrder(
                    $order
                );

                /**
                 * Checking order invoice status
                 */
                if ($order->getBuyerInvoice()
                    ->getStatus()
                    ->isPaid()
                ) {

                    /**
                     * Updating user balance status
                     */
                    $userBalanceRepository->updateStatus(
                        $order->buyer->getBuyerBalance(),
                        UserBalanceStatusList::getActive()
                    );

                    /**
                     * Creating order item pending requests
                     */
                    $orderItemService->createPendingRequests(
                        $order->items
                    );

                    /**
                     * Processing order items
                     */
                    $orderItemService->processPaidOrderItems(
                        $order
                    );

                    try {

                        /**
                         * Creating order invoice log
                         */
                        $this->logService->addInvoiceForBuyerLog(
                            $order->getBuyerInvoice(),
                            $order->buyer->getBuyerBalance(),
                            UserBalanceTypeList::getBuyer(),
                            'paid'
                        );
                    } catch (LogException) {

                    }

                    /**
                     * Creating sale overviews
                     */
                    $saleOverviews = $this->saleService->createForOrder(
                        $order
                    );

                    try {

                        /**
                         * Creating sale overview logs
                         */
                        $this->saleService->addTransactionLogs(
                            $saleOverviews,
                            'created'
                        );
                    } catch (LogException) {

                    }

                    /** @var OrderItem $orderItem */
                    foreach ($order->items as $orderItem) {
                        app(OrderItemRepository::class)->updateStatus(
                            $orderItem,
                            OrderItemStatusList::getFinished()
                        );

                        $this->orderItemRepository->updateExpiredAt(
                            $orderItem,
                            $orderItem->created_at->addHours(3)
                        );

                        $this->orderItemRepository->updateAcceptedAt(
                            $orderItem,
                            $orderItem->created_at->addHours(rand(1, 12))
                        );

                        $this->orderItemRepository->updateFinishedAt(
                            $orderItem,
                            $orderItem->timeslot
                                ->datetime_to
                                ->addHours(rand(1, 12))
                        );

                        /**
                         * Creating seller invoices
                         */
                        $sellerInvoices = $this->invoiceService->createForSellers(
                            $orderItem->order
                        );

                        try {

                            /**
                             * Creating order invoice for seller logs
                             */
                            $this->invoiceService->addSellerTransactionLogs(
                                $sellerInvoices,
                                'created'
                            );
                        } catch (LogException) {

                        }

                        try {

                            /**
                             * Creating order invoice for seller logs
                             */
                            $this->invoiceService->addSellerTransactionLogs(
                                $sellerInvoices,
                                'paid'
                            );
                        } catch (LogException) {

                        }
                    }
                }
            } else {
                if ($result == 'canceled') {
                    $this->orderService->cancelPayment(
                        $order
                    );
                } elseif ($result == 'reschedule') {

                    /** @var OrderItem $orderItem */
                    foreach ($order->items as $orderItem) {
                        $this->orderItemRepository->updateStatus(
                            $orderItem,
                            OrderItemStatusList::getReschedule()
                        );

                        $this->orderItemRepository->updateExpiredAt(
                            $orderItem,
                            $orderItem->created_at->addHours(3)
                        );

                        if (rand(0 ,1)) {
                            $this->orderItemRepository->updateAcceptedAt(
                                $orderItem,
                                $orderItem->created_at->addHours(rand(1, 12))
                            );
                        }
                    }
                } elseif ($result == 'disputed') {

                    /** @var OrderItem $orderItem */
                    foreach ($order->items as $orderItem) {
                        $this->orderItemRepository->updateExpiredAt(
                            $orderItem,
                            $orderItem->created_at->addHours(3)
                        );

                        $this->orderItemRepository->updateAcceptedAt(
                            $orderItem,
                            $orderItem->created_at->addHours(rand(1, 12))
                        );

                        $this->orderItemRepository->updateStatus(
                            $orderItem,
                            OrderItemStatusList::getDisputed()
                        );
                    }
                } elseif ($result == 'finished_dispute') {

                    /** @var OrderItem $orderItem */
                    foreach ($order->items as $orderItem) {
                        $this->orderItemRepository->updateExpiredAt(
                            $orderItem,
                            $orderItem->created_at->addHours(3)
                        );

                        $this->orderItemRepository->updateAcceptedAt(
                            $orderItem,
                            $orderItem->created_at->addHours(rand(1, 12))
                        );

                        $this->orderItemRepository->updateStatus(
                            $orderItem,
                            OrderItemStatusList::getFinishedDispute()
                        );

                        $paymentStatus = [
                            1 => OrderItemPaymentStatusList::getPaidPartialRefund(),
                            2 => OrderItemPaymentStatusList::getRefunded(),
                            3 => OrderItemPaymentStatusList::getChargeback()
                        ];

                        $this->orderItemRepository->updatePaymentStatus(
                            $orderItem,
                            $paymentStatus[rand(1, 3)]
                        );
                    }
                } elseif ($result == 'canceled_dispute') {

                    /** @var OrderItem $orderItem */
                    foreach ($order->items as $orderItem) {
                        $this->orderItemRepository->updateExpiredAt(
                            $orderItem,
                            $orderItem->created_at->addHours(3)
                        );

                        $this->orderItemRepository->updateAcceptedAt(
                            $orderItem,
                            $orderItem->created_at->addHours(rand(1, 12))
                        );

                        $this->orderItemRepository->updateStatus(
                            $orderItem,
                            OrderItemStatusList::getCanceledDispute()
                        );
                    }
                }
            }
        }
    }
}
