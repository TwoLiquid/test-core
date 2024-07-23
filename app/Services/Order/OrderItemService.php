<?php

namespace App\Services\Order;

use App\Exceptions\DatabaseException;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusListItem;
use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MySql\Billing;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Sale;
use App\Models\MySql\Tip\TipInvoice;
use App\Models\MySql\User\User;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Order\OrderItemPendingRequestRepository;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\Notification\EmailNotificationService;
use App\Services\Order\Interfaces\OrderItemServiceInterface;
use App\Services\Payment\PaymentMethodService;
use App\Services\Schedule\ScheduleService;
use App\Services\Timeslot\TimeslotService;
use Illuminate\Database\Eloquent\Collection;
use App\Settings\User\HandlingFeesSetting as UserHandlingFeesSetting;
use App\Settings\Vybe\HandlingFeesSetting as VybeHandlingFeesSetting;

/**
 * Class OrderItemService
 *
 * @package App\Services\Order
 */
class OrderItemService implements OrderItemServiceInterface
{
    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var PaymentMethodService
     */
    protected PaymentMethodService $paymentMethodService;

    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderItemPendingRequestRepository
     */
    protected OrderItemPendingRequestRepository $orderItemPendingRequestRepository;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var ScheduleRepository
     */
    protected ScheduleRepository $scheduleRepository;

    /**
     * @var ScheduleService
     */
    protected ScheduleService $scheduleService;

    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserHandlingFeesSetting
     */
    protected UserHandlingFeesSetting $userHandlingFeesSetting;

    /**
     * @var VybeHandlingFeesSetting
     */
    protected VybeHandlingFeesSetting $vybeHandlingFeesSetting;

    /**
     * OrderItemService constructor
     */
    public function __construct()
    {
        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderItemPendingRequestRepository orderItemPendingRequestRepository */
        $this->orderItemPendingRequestRepository = new OrderItemPendingRequestRepository();

        /** @var OrderRepository orderRepository */
        $this->orderRepository = new OrderRepository();

        /** @var ScheduleRepository scheduleRepository */
        $this->scheduleRepository = new ScheduleRepository();

        /** @var ScheduleService scheduleService */
        $this->scheduleService = new ScheduleService();

        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var UserHandlingFeesSetting userHandlingFeesSetting */
        $this->userHandlingFeesSetting = new UserHandlingFeesSetting();

        /** @var VybeHandlingFeesSetting vybeHandlingFeesSetting */
        $this->vybeHandlingFeesSetting = new VybeHandlingFeesSetting();
    }

    /**
     * @param Order $order
     * @param array $orderItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function createOrderItems(
        Order $order,
        array $orderItems
    ) : Collection
    {
        /**
         * Preparing order items collections
         */
        $outputOrderItems = new Collection();

        /** @var array $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Getting an appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->findById(
                $orderItem['appearance_case_id']
            );

            /**
             * Checking timeslot id existence
             */
            if (isset($orderItem['timeslot_id'])) {

                /**
                 * Getting timeslot
                 */
                $timeslot = $this->timeslotRepository->findById(
                    $orderItem['timeslot_id']
                );
            } else {

                /**
                 * Getting timeslot
                 */
                $timeslot = $this->timeslotRepository->findForVybeBetweenDates(
                    $appearanceCase->vybe,
                    $orderItem['datetime_from'],
                    $orderItem['datetime_to']
                );
            }

            if (!$timeslot) {

                /**
                 * Creating timeslot
                 */
                $timeslot = $this->timeslotRepository->store(
                    $orderItem['datetime_from'],
                    $orderItem['datetime_to']
                );
            }

            /**
             * Checking vybe type
             */
            if (!$appearanceCase->vybe
                ->getType()
                ->isSolo()
            ) {

                /**
                 * Attaching user to timeslot
                 */
                $this->timeslotRepository->attachUser(
                    $timeslot,
                    $order->buyer
                );
            }

            /**
             * Setting vybe settings
             */
            $this->vybeHandlingFeesSetting->setVybe(
                $appearanceCase->vybe
            );

            /**
             * Getting vybe setting tip handling fee
             */
            $sellerHandlingFee = $this->vybeHandlingFeesSetting->getVybeSellerHandlingFee();

            /**
             * Checking seller handling fee
             */
            if (!$sellerHandlingFee) {

                /**
                 * Setting user settings
                 */
                $this->userHandlingFeesSetting->setUser(
                    $appearanceCase->vybe->user
                );

                /**
                 * Getting user setting tip handling fee
                 */
                $sellerHandlingFee = $this->userHandlingFeesSetting->getSellerHandlingFee();
            }

            /**
             * Counting all about user / vybe handling fee
             */
            $handlingFee = round($appearanceCase->price * ($sellerHandlingFee / 100), 2);

            /**
             * Getting amount earned
             */
            $amountEarned = $appearanceCase->price - $handlingFee;

            /**
             * Getting amount tax
             */
            $amountTax = $this->getAmountTax(
                $order->buyer->billing,
                $appearanceCase->price
            );

            /**
             * Getting amount total
             */
            $amountTotal = ($appearanceCase->price + $amountTax) * (int) $orderItem['quantity'];

            /**
             * Creating order item for order
             */
            $orderItem = $this->orderItemRepository->store(
                $order,
                $appearanceCase->vybe,
                $appearanceCase->vybe->user,
                $appearanceCase,
                $timeslot,
                OrderItemStatusList::getUnpaid(),
                OrderItemPaymentStatusList::getUnpaid(),
                $appearanceCase->vybe->version,
                $appearanceCase->price,
                $orderItem['quantity'],
                $amountEarned,
                $amountTotal,
                $amountTax,
                $handlingFee
            );

            /**
             * Adding order items to a collection
             */
            $outputOrderItems->add(
                $orderItem
            );
        }

        return $outputOrderItems;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function createPendingRequests(
        Collection $orderItems
    ) : Collection
    {
        /**
         * Preparing order item pending requests variable
         */
        $orderItemPendingRequests = new Collection();

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Creating order item pending request
             */
            $orderItemPendingRequest = $this->orderItemPendingRequestRepository->store(
                $orderItem,
                $orderItem->order->buyer,
                $orderItem->seller,
                $orderItem->order->buyer,
                OrderItemRequestInitiatorList::getBuyer(),
                OrderItemStatusList::getUnpaid()
            );

            /**
             * Adding order item pending request to response
             */
            $orderItemPendingRequests->push(
                $orderItemPendingRequest
            );
        }

        return $orderItemPendingRequests;
    }

    /**
     * @param Order $order
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function processPaidOrderItems(
        Order $order
    ) : Collection
    {
        /** @var OrderItem $orderItem */
        foreach ($order->items as $orderItem) {

            /**
             * Updating user balance
             */
            $this->userBalanceRepository->increasePendingAmount(
                $orderItem->seller->getSellerBalance(),
                $orderItem->amount_earned * $orderItem->quantity
            );
        }

        /**
         * Checking order payment method
         */
        if ($this->paymentMethodService->isBalance(
            $order->method
        )) {

            /**
             * Updating user balance
             */
            $this->userBalanceRepository->decreaseAmount(
                $orderItem->order
                    ->buyer
                    ->getBuyerBalance(),
                $orderItem->order->amount_total
            );
        }

        return $order->items;
    }

    /**
     * @param User $buyer
     * @param array $orderItems
     *
     * @return float
     *
     * @throws DatabaseException
     */
    public function getPreAmountTotal(
        User $buyer,
        array $orderItems
    ) : float
    {
        $amountTotal = 0;

        /** @var array $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Getting an appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->findById(
                $orderItem['appearance_case_id']
            );

            /**
             * Getting amount tax
             */
            $amountTax = $this->getAmountTax(
                $buyer->billing,
                $appearanceCase->price
            );

            /**
             * Getting amount total
             */
            $amountTotal += ($appearanceCase->price + $amountTax) * (int) $orderItem['quantity'];
        }

        return round(
            $amountTotal,
            2
        );
    }

    /**
     * @param Billing $billing
     * @param float $amount
     *
     * @return float
     */
    public function getAmountTax(
        Billing $billing,
        float $amount
    ) : float
    {
        /**
         * Preparing amount tax variable
         */
        $amountTax = 0;

        /**
         * Getting tax rule country
         */
        $taxRuleCountry = $billing->countryPlace ?
            $billing->countryPlace->taxRuleCountry :
            null;

        /**
         * Getting a tax rule region
         */
        $taxRuleRegion = $billing->regionPlace ?
            $billing->regionPlace->taxRuleRegion :
            null;

        /**
         * Checking tax rule country existence
         */
        if ($taxRuleCountry) {

            /**
             * Counting amount tax
             */
            $amountTax += round(
                $amount / 100 * $taxRuleCountry->tax_rate,
                2
            );
        }

        /**
         * Checking a tax rule region existence
         */
        if ($taxRuleRegion) {

            /**
             * Counting amount tax
             */
            $amountTax += round(
                $amount / 100 * $taxRuleRegion->tax_rate,
                2
            );
        }

        return round(
            $amountTax,
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getVybesTypes(
        Collection $orderItems
    ) : Collection
    {
        /**
         * Preparing vybe types response collection
         */
        $vybeTypes = new Collection();

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Getting vybe
             */
            $vybe = $orderItem->appearanceCase->vybe;

            /**
             * Checking is vybe types response collection empty
             */
            if (empty($vybeTypes)) {

                /**
                 * Adding vybe type
                 */
                $vybeTypes->add(
                    $vybe->getType()
                );
            } else {

                /**
                 * Preparing match flag
                 */
                $match = false;

                /** @var VybeTypeListItem $vybeType */
                foreach ($vybeTypes as $vybeType) {

                    /**
                     * Checking vybe type existence
                     */
                    if ($vybe->getType()->code == $vybeType->code) {
                        $match = true;
                    }
                }

                if ($match === false) {

                    /**
                     * Adding vybe type
                     */
                    $vybeTypes->add(
                        $vybe->getType()
                    );
                }
            }
        }

        return $vybeTypes;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getOrderItemsStatuses(
        Collection $orderItems
    ) : Collection
    {
        /**
         * Preparing order items statuses response collection
         */
        $orderItemsStatuses = new Collection();

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Adding order item status to a collection
             */
            $orderItemsStatuses->add(
                $orderItem->getStatus()
            );
        }

        /** @var OrderItemStatusListItem $orderItemsStatus */
        foreach ($orderItemsStatuses as $orderItemsStatus) {

            /**
             * Updating order item status count
             */
            $orderItemsStatus->setCount(
                $orderItems->where(
                    'status_id',
                    $orderItemsStatus->id
                )->count()
            );
        }

        return $orderItemsStatuses;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getOrderItemsPaymentStatuses(
        Collection $orderItems
    ) : Collection
    {
        /**
         * Preparing order items payment statuses response collection
         */
        $orderItemPaymentStatuses = new Collection();

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Adding order item payment status to a collection
             */
            $orderItemPaymentStatuses->add(
                $orderItem->getPaymentStatus()
            );
        }

        /** @var OrderItemPaymentStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemPaymentStatuses as $orderItemPaymentStatus) {

            /**
             * Updating order item payment status count
             */
            $orderItemPaymentStatus->setCount(
                $orderItems->where(
                    'payment_status_id',
                    $orderItemPaymentStatus->id
                )->count()
            );
        }

        return $orderItemPaymentStatuses->unique('code');
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getAmountTotal(
        Collection $orderItems
    ) : float
    {
        /**
         * Preparing order items amount total
         */
        $amountTotal = 0;

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Counting order items amount total
             */
            $amountTotal += $orderItem->amount_total;
        }

        return round(
            $amountTotal,
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getPriceTotal(
        Collection $orderItems
    ) : float
    {
        /**
         * Preparing order items price total
         */
        $priceTotal = 0;

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Counting order items price total
             */
            $priceTotal += $orderItem->quantity * $orderItem->price;
        }

        return round(
            $priceTotal,
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getTotalHandlingFee(
        Collection $orderItems
    ) : float
    {
        /**
         * Preparing order items total handling fee
         */
        $totalHandlingFee = 0;

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Counting order items total handling fee
             */
            $totalHandlingFee += $orderItem->quantity * $orderItem->handling_fee;
        }

        return round(
            $totalHandlingFee,
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getTotalAmountEarned(
        Collection $orderItems
    ) : float
    {
        /**
         * Preparing order items total amount earned
         */
        $totalAmountEarned = 0;

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Counting order items total amount earned
             */
            $totalAmountEarned += $orderItem->quantity * $orderItem->amount_earned;
        }

        return round(
            $totalAmountEarned,
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getTotalAmountTax(
        Collection $orderItems
    ) : float
    {
        /**
         * Preparing order items total amount tax
         */
        $totalAmountTax = 0;

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Counting order items total amount tax
             */
            $totalAmountTax += $orderItem->quantity * $orderItem->amount_tax;
        }

        return round(
            $totalAmountTax,
            2
        );
    }

    /**
     * @param Collection $orders
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminPaymentStatusesByOrdersIds(
        Collection $orders
    ) : Collection
    {
        $orderItemsIds = [];

        /** @var Order $order */
        foreach ($orders as $order) {

            /** @var OrderItem $orderItem */
            foreach ($order->items as $orderItem) {

                if (!in_array($orderItem->id, $orderItemsIds)) {
                    $orderItemsIds[] = $orderItem->id;
                }
            }
        }

        /**
         * Getting order item payment statuses count
         */
        $orderItemPaymentStatusesCounts = $this->orderItemRepository->getPaymentStatusesByIdsCount(
            $orderItemsIds
        );

        /**
         * Getting vybe statuses
         */
        $orderItemPaymentStatuses = OrderItemPaymentStatusList::getItems();

        /** @var OrderItemPaymentStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemPaymentStatuses as $orderItemPaymentStatus) {

            /**
             * Setting vybe status count
             */
            $orderItemPaymentStatus->setCount(
                $orderItemPaymentStatusesCounts->getAttribute(
                    $orderItemPaymentStatus->code
                )
            );
        }

        return $orderItemPaymentStatuses;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminPaymentStatusesByIds(
        Collection $orderItems
    ) : Collection
    {
        $orderItemsIds = [];

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            if (!in_array($orderItem->id, $orderItemsIds)) {
                $orderItemsIds[] = $orderItem->id;
            }
        }

        /**
         * Getting order item payment statuses count
         */
        $orderItemPaymentStatusesCounts = $this->orderItemRepository->getPaymentStatusesByIdsCount(
            $orderItemsIds
        );

        /**
         * Getting order item statuses
         */
        $orderItemPaymentStatuses = OrderItemPaymentStatusList::getItems();

        /** @var OrderItemPaymentStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemPaymentStatuses as $orderItemPaymentStatus) {

            /**
             * Setting order item statuses count
             */
            $orderItemPaymentStatus->setCount(
                $orderItemPaymentStatusesCounts->getAttribute(
                    $orderItemPaymentStatus->code
                )
            );
        }

        return $orderItemPaymentStatuses;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesByIds(
        Collection $orderItems
    ) : Collection
    {
        $orderItemsIds = [];

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            if (!in_array($orderItem->id, $orderItemsIds)) {
                $orderItemsIds[] = $orderItem->id;
            }
        }

        /**
         * Getting order item statuses count
         */
        $orderItemStatusesCounts = $this->orderItemRepository->getStatusesByIdsCount(
            $orderItemsIds
        );

        /**
         * Getting order items statuses
         */
        $orderItemStatuses = OrderItemStatusList::getItems();

        /** @var OrderItemStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemStatuses as $orderItemStatus) {

            /**
             * Setting order item statuses count
             */
            $orderItemStatus->setCount(
                $orderItemStatusesCounts->getAttribute(
                    $orderItemStatus->code
                )
            );
        }

        return $orderItemStatuses;
    }

    /**
     * @param Collection $orderInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminPaymentStatusesByOrderInvoicesIds(
        Collection $orderInvoices
    ) : Collection
    {
        $orderItemsIds = [];

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {

            /** @var OrderItem $orderItem */
            foreach ($orderInvoice->items as $orderItem) {
                if (!in_array($orderItem->id, $orderItemsIds)) {
                    $orderItemsIds[] = $orderItem->id;
                }
            }
        }

        /**
         * Getting order item payment statuses count
         */
        $orderItemPaymentStatusesCounts = $this->orderItemRepository->getPaymentStatusesByIdsCount(
            $orderItemsIds
        );

        /**
         * Getting order item statuses
         */
        $orderItemPaymentStatuses = OrderItemPaymentStatusList::getItems();

        /** @var OrderItemPaymentStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemPaymentStatuses as $orderItemPaymentStatus) {

            /**
             * Setting order item statuses count
             */
            $orderItemPaymentStatus->setCount(
                $orderItemPaymentStatusesCounts->getAttribute(
                    $orderItemPaymentStatus->code
                )
            );
        }

        return $orderItemPaymentStatuses;
    }

    /**
     * @param Collection $orderInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesByOrderInvoicesIds(
        Collection $orderInvoices
    ) : Collection
    {
        $orderItemsIds = [];

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {

            /** @var OrderItem $orderItem */
            foreach ($orderInvoice->items as $orderItem) {
                if (!in_array($orderItem->id, $orderItemsIds)) {
                    $orderItemsIds[] = $orderItem->id;
                }
            }
        }

        /**
         * Getting order item statuses count
         */
        $orderItemStatusesCounts = $this->orderItemRepository->getStatusesByIdsCount(
            $orderItemsIds
        );

        /**
         * Getting order items statuses
         */
        $orderItemStatuses = OrderItemStatusList::getItems();

        /** @var OrderItemStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemStatuses as $orderItemStatus) {

            /**
             * Setting order item statuses count
             */
            $orderItemStatus->setCount(
                $orderItemStatusesCounts->getAttribute(
                    $orderItemStatus->code
                )
            );
        }

        return $orderItemStatuses;
    }

    /**
     * @param Collection $sales
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminPaymentStatusesBySalesIds(
        Collection $sales
    ) : Collection
    {
        $orderItemsIds = [];

        /** @var Sale $sale */
        foreach ($sales as $sale) {

            /** @var OrderItem $orderItem */
            foreach ($sale->items as $orderItem) {

                if (!in_array($orderItem->id, $orderItemsIds)) {
                    $orderItemsIds[] = $orderItem->id;
                }
            }
        }

        /**
         * Getting order item payment statuses count
         */
        $orderItemPaymentStatusesCounts = $this->orderItemRepository->getPaymentStatusesByIdsCount(
            $orderItemsIds
        );

        /**
         * Getting vybe statuses
         */
        $orderItemPaymentStatuses = OrderItemPaymentStatusList::getItems();

        /** @var OrderItemPaymentStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemPaymentStatuses as $orderItemPaymentStatus) {

            /**
             * Setting vybe status count
             */
            $orderItemPaymentStatus->setCount(
                $orderItemPaymentStatusesCounts->getAttribute(
                    $orderItemPaymentStatus->code
                )
            );
        }

        return $orderItemPaymentStatuses;
    }

    /**
     * @param Collection $orderItems
     *
     * @return array
     */
    public function groupOrderItemsBySellers(
        Collection $orderItems
    ) : array
    {
        /**
         * Grouping order items by sellers
         */
        $groupedOrderItems = $orderItems->groupBy('appearanceCase.vybe.user.auth_id');

        $sellersArr = [];

        /** @var array $orderItems */
        foreach ($groupedOrderItems as $orderItems) {
            $sellersArr[] = [
                'user'  => $orderItems[0]->appearanceCase->vybe->user,
                'items' => $orderItems
            ];
        }

        return $sellersArr;
    }

    /**
     * @param Collection $tipInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesByTipInvoicesIds(
        Collection $tipInvoices
    ) : Collection
    {
        $orderItemsIds = [];

        /** @var TipInvoice $tipInvoice */
        foreach ($tipInvoices as $tipInvoice) {
            $orderItemsIds[] = $tipInvoice->tip->item_id;
        }

        /**
         * Getting order item statuses count
         */
        $orderItemStatusesCounts = $this->orderItemRepository->getStatusesByIdsCount(
            $orderItemsIds
        );

        /**
         * Getting order items statuses
         */
        $orderItemStatuses = OrderItemStatusList::getItems();

        /** @var OrderItemStatusListItem $orderItemPaymentStatus */
        foreach ($orderItemStatuses as $orderItemStatus) {

            /**
             * Setting order item statuses count
             */
            $orderItemStatus->setCount(
                $orderItemStatusesCounts->getAttribute(
                    $orderItemStatus->code
                )
            );
        }

        return $orderItemStatuses;
    }

    /**
     * @param Collection $orderItems
     *
     * @return void
     */
    public function sendToAllSellers(
        Collection $orderItems
    ) : void
    {
        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Sending email notification to seller
             */
            $this->emailNotificationService->sendSaleOrderNew(
                $orderItem->seller,
                $orderItem->vybe
            );
        }
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getAcceptAverageSeconds(
        Collection $orderItems
    ) : int
    {
        $timePeriods = [];

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            if ($orderItem->accepted_at) {
                $timePeriods[] = $orderItem->accepted_at
                    ->diffInSeconds(
                        $orderItem->created_at
                    );
            }
        }

        return ceil(
            array_sum($timePeriods) / count($timePeriods)
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getTotalCount(
        Collection $orderItems
    ) : int
    {
        return $orderItems->count();
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getCanceledCount(
        Collection $orderItems
    ) : int
    {
        return $orderItems->where('status_id', '=', OrderItemStatusList::getCanceled()->id)
            ->count();
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getCanceledPercentage(
        Collection $orderItems
    ) : float
    {
        return round(
            $this->getCanceledCount($orderItems) * 100 / $orderItems->count(),
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getExpiredCount(
        Collection $orderItems
    ) : int
    {
        $count = 0;

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            //TODO: Think about default expire period

            if ($orderItem->expired_at &&
                $orderItem->expired_at->diffInHours($orderItem->created_at) > 1
            ) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getExpiredPercentage(
        Collection $orderItems
    ) : float
    {
        return round(
            $this->getExpiredCount($orderItems) * 100 / $orderItems->count(),
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getDisputedCount(
        Collection $orderItems
    ) : int
    {
        return $orderItems->where('status_id', '=', OrderItemStatusList::getDisputed()->id)
            ->count();
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getDisputedPercentage(
        Collection $orderItems
    ) : float
    {
        return round(
            $this->getDisputedCount($orderItems) * 100 / $orderItems->count(),
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getCanceledDisputeCount(
        Collection $orderItems
    ) : int
    {
        return $orderItems->where('status_id', '=', OrderItemStatusList::getCanceledDispute()->id)
            ->count();
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getCanceledDisputePercentage(
        Collection $orderItems
    ) : float
    {
        return round(
            $this->getCanceledDisputeCount($orderItems) * 100 / $orderItems->count(),
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getFinishedDisputeCount(
        Collection $orderItems
    ) : int
    {
        return $orderItems->where('status_id', '=', OrderItemStatusList::getFinishedDispute()->id)
            ->count();
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getFinishedDisputePercentage(
        Collection $orderItems
    ) : float
    {
        return round(
            $this->getFinishedDisputeCount($orderItems) * 100 / $orderItems->count(),
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getPartialRefundDisputeCount(
        Collection $orderItems
    ) : int
    {
        return $orderItems->where('status_id', '=', OrderItemStatusList::getCanceled()->id)
            ->where('payment_status_id', '=', OrderItemPaymentStatusList::getPaidPartialRefund()->id)
            ->count();
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getPartialRefundDisputePercentage(
        Collection $orderItems
    ) : float
    {
        return round(
            $this->getPartialRefundDisputeCount($orderItems) * 100 / $orderItems->count(),
            2
        );
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getFinishedCount(
        Collection $orderItems
    ) : int
    {
        return $orderItems->where('status_id', '=', OrderItemStatusList::getFinished()->id)
            ->count();
    }

    /**
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getFinishedPercentage(
        Collection $orderItems
    ) : float
    {
        return round(
            $this->getFinishedCount($orderItems) * 100 / $orderItems->count(),
            2
        );
    }
}
