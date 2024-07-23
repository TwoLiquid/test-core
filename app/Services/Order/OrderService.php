<?php

namespace App\Services\Order;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Lists\Currency\CurrencyList;
use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Payment\Type\PaymentTypeList;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderTransactionRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\Interfaces\OrderServiceInterface;
use App\Services\Payment\PayPalService;
use App\Services\Sale\SaleService;
use App\Support\Service\PayPal\TransactionResponse;
use Illuminate\Database\Eloquent\Collection;
use JsonMapper_Exception;

/**
 * Class OrderService
 *
 * @package App\Services\Order
 */
class OrderService implements OrderServiceInterface
{
    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var InvoiceService
     */
    protected InvoiceService $invoiceService;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var PayPalService
     */
    protected PayPalService $payPalService;

    /**
     * @var OrderInvoiceRepository
     */
    protected OrderInvoiceRepository $orderInvoiceRepository;

    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var OrderTransactionRepository
     */
    protected OrderTransactionRepository $orderTransactionRepository;

    /**
     * @var SaleService
     */
    protected SaleService $saleService;

    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * OrderService constructor
     */
    public function __construct()
    {
        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PayPalService payPalService */
        $this->payPalService = new PayPalService();

        /** @var OrderInvoiceRepository orderInvoiceRepository */
        $this->orderInvoiceRepository = new OrderInvoiceRepository();

        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var OrderRepository orderRepository */
        $this->orderRepository = new OrderRepository();

        /** @var OrderTransactionRepository orderTransactionRepository */
        $this->orderTransactionRepository = new OrderTransactionRepository();

        /** @var SaleService saleService */
        $this->saleService = new SaleService();

        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();
    }

    /**
     * @param User $buyer
     * @param PaymentMethod $paymentMethod
     * @param array $orderItems
     *
     * @return Order|null
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function createOrder(
        User $buyer,
        PaymentMethod $paymentMethod,
        array $orderItems
    ) : ?Order
    {
        /**
         * Returning created order
         */
        $order = $this->orderRepository->store(
            $buyer,
            $paymentMethod
        );

        /**
         * Checking order existence
         */
        if (!$order) {
            throw new BaseException(
                trans('exceptions/service/order/order.' . __FUNCTION__ . '.order'),
                null,
                422
            );
        }

        /**
         * Creating order items for order
         */
        $orderItems = $this->orderItemService->createOrderItems(
            $order,
            $orderItems
        );

        /**
         * Getting order amount
         */
        $amount = $this->orderItemService->getPriceTotal(
            $orderItems
        );

        /**
         * Getting order amount tax
         */
        $amountTax = $this->orderItemService->getTotalAmountTax(
            $orderItems
        );

        /**
         * Updating order amount
         */
        $order = $this->orderRepository->updateAmount(
            $order,
            $amount,
            $amountTax
        );

        /**
         * Getting payment fee amount
         */
        $paymentFee = $this->getPaymentFee(
            $paymentMethod,
            $order->amount
        );

        /**
         * Getting payment fee amount tax
         */
        $paymentFeeTax = $this->orderItemService->getAmountTax(
            $buyer->billing,
            $paymentFee
        );

        /**
         * Updating order payment fee and payment fee tax
         */
        $order = $this->orderRepository->updatePaymentFee(
            $order,
            $paymentFee,
            $paymentFeeTax
        );

        /**
         * Getting order amount total
         */
        $amountTotal = $this->getAmountTotal(
            $order,
            $orderItems
        );

        /**
         * Updating amount total
         */
        $order = $this->orderRepository->updateAmountTotal(
            $order,
            $amountTotal
        );

        /**
         * Creating buyer invoice
         */
        $this->invoiceService->createForBuyer(
            $order
        );

        /**
         * Getting order
         */
        return $this->orderRepository->findFullById(
            $order->id
        );
    }

    /**
     * @param Order $order
     * @param string $hash
     *
     * @return bool
     */
    public function checkHash(
        Order $order,
        string $hash
    ) : bool
    {
        return strcmp($order->hash, $hash) == 0;
    }

    /**
     * @param Order $order
     * @param Collection $orderItems
     *
     * @return float
     */
    public function getAmountTotal(
        Order $order,
        Collection $orderItems
    ) : float
    {
        /**
         * Getting order items amount total
         */
        $amount = $this->orderItemService->getAmountTotal(
            $orderItems
        );

        return round(
            array_sum([
                $amount,
                $order->payment_fee,
                $order->payment_fee_tax
            ]), 2
        );
    }

    /**
     * @param Order $order
     *
     * @return string|null
     *
     * @throws BaseException
     */
    public function getPaymentUrl(
        Order $order
    ) : ?string
    {
        /**
         * Checking payment method
         */
        if ($order->method->code == 'paypal') {

            /**
             * Getting PayPal payment url
             */
            return $this->payPalService->getPaymentUrl(
                PaymentTypeList::getOrder(),
                CurrencyList::getUsd(),
                $order->id,
                $order->amount_total,
                $order->hash
            );
        }

        return null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param float|null $amount
     *
     * @return float
     */
    public function getPaymentFee(
        PaymentMethod $paymentMethod,
        ?float $amount
    ) : float
    {
        /**
         * Preparing payment fee variable
         */
        $paymentFee = 0;

        /**
         * Checking payment fee and amount existence
         */
        if ($paymentMethod->payment_fee &&
            $amount
        ) {

            /**
             * Getting payment fee
             */
            $paymentFee = round(
                $amount / 100 * $paymentMethod->payment_fee,
                2
            );
        }

        return round(
            $paymentFee,
            2
        );
    }

    /**
     * @param Order $order
     *
     * @return Order
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws JsonMapper_Exception
     */
    public function executePayment(
        Order $order
    ) : Order
    {
        /**
         * Checking a payment method type
         */
        if ($order->method->code == 'balance') {

            /**
             * Creating order transaction
             */
            $this->orderTransactionRepository->store(
                $order->getBuyerInvoice(),
                $order->method,
                null,
                $order->amount_total,
                null,
                null
            );
        }

        /**
         * Checking a payment method type
         */
        if ($order->method->code == 'paypal') {

            /**
             * Executing payment transaction response
             */
            $transactions = $this->payPalService->executePayment(
                PaymentTypeList::getOrder(),
                $order->id,
                $order->hash
            );

            /** @var TransactionResponse $transaction */
            foreach ($transactions as $transaction) {

                /**
                 * Creating order transaction
                 */
                $this->orderTransactionRepository->store(
                    $order->getBuyerInvoice(),
                    $order->method,
                    $transaction->id,
                    $transaction->totalAmount,
                    $transaction->transactionFee,
                    $transaction->description
                );
            }
        }

        return $this->orderRepository->findFullById(
            $order->id
        );
    }

    /**
     * @param Order $order
     *
     * @return Order
     *
     * @throws DatabaseException
     */
    public function cancelPayment(
        Order $order
    ) : Order
    {
        /** @var OrderInvoice $orderInvoice */
        foreach ($order->invoices as $orderInvoice) {

            /**
             * Updating invoice status
             */
            $this->orderInvoiceRepository->updateStatus(
                $orderInvoice,
                InvoiceStatusList::getCanceled()
            );
        }

        /** @var OrderItem $orderItem */
        foreach ($order->items as $orderItem) {

            /**
             * Updating order item statuses
             */
            $this->orderItemRepository->updateStatuses(
                $orderItem,
                OrderItemStatusList::getCanceled(),
                OrderItemPaymentStatusList::getCanceled()
            );
        }

        return $this->orderRepository->findFullById(
            $order->id
        );
    }

    /**
     * @param Order $order
     *
     * @return Order
     *
     * @throws DatabaseException
     */
    public function updatePaidOrder(
        Order $order
    ) : Order
    {
        /**
         * Updating order paid at
         */
        $this->orderRepository->updatePaidAt(
            $order
        );

        /** @var OrderInvoice $orderInvoice */
        foreach ($order->invoices as $orderInvoice) {

            /**
             * Checking order an invoice type
             */
            if ($orderInvoice->getType()->isBuyer()) {

                /**
                 * Updating invoice status
                 */
                $this->orderInvoiceRepository->updateStatus(
                    $orderInvoice,
                    InvoiceStatusList::getPaid()
                );
            } elseif ($orderInvoice->getType()->isSeller()) {

                /**
                 * Updating invoice status
                 */
                $this->orderInvoiceRepository->updateStatus(
                    $orderInvoice,
                    InvoiceStatusList::getPendingPayout()
                );
            }
        }

        /** @var OrderItem $orderItem */
        foreach ($order->items as $orderItem) {

            /**
             * Updating order item statuses
             */
            $this->orderItemRepository->updateStatuses(
                $orderItem,
                OrderItemStatusList::getPending(),
                OrderItemPaymentStatusList::getPaid()
            );

            /**
             * Update order item previous status
             */
            $this->orderItemRepository->updatePreviousStatus(
                $orderItem,
                OrderItemStatusList::getUnpaid()
            );
        }

        return $this->orderRepository->findFullById(
            $order->id
        );
    }

    /**
     * @param Order $order
     *
     * @return float
     */
    public function getItemsTotalHandlingFee(
        Order $order
    ) : float
    {
        /**
         * Preparing total handling fee
         */
        $totalHandlingFee = 0;

        /** @var OrderItem $orderItem */
        foreach ($order->items as $orderItem) {

            /**
             * Counting total handling fee
             */
            $totalHandlingFee = round(
                (float) $totalHandlingFee + $orderItem->handling_fee,
                2
            );
        }

        return $totalHandlingFee;
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getUniqueSellersCount(
        Collection $orderItems
    ) : int
    {
        $uniqueSellersCount = [];

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Setting unique seller id
             */
            if (!in_array($orderItem->appearanceCase->vybe->user->id, $uniqueSellersCount)) {
                $uniqueSellersCount[] = $orderItem->appearanceCase->vybe->user->id;
            }
        }

        /**
         * Convert unique sellers ids array to amount
         */
        return count($uniqueSellersCount);
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getUniqueSellers(
        Collection $orderItems
    ) : Collection
    {
        return $orderItems->unique(function ($orderItem) {
            return $orderItem->appearanceCase->vybe->user->id;
        })->map(function ($item) {
            return $item->appearanceCase->vybe->user;
        });
    }

    /**
     * @param Collection $orderItems
     *
     * @return int
     */
    public function getUniqueBuyersCount(
        Collection $orderItems
    ) : int
    {
        $uniqueBuyersCount = [];

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /**
             * Setting unique buyer id
             */
            if (!in_array($orderItem->order->buyer_id, $uniqueBuyersCount)) {
                $uniqueBuyersCount[] = $orderItem->order->buyer_id;
            }
        }

        /**
         * Convert a unique buyers ids array to amount
         */
        return count($uniqueBuyersCount);
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getUniqueBuyers(
        Collection $orderItems
    ) : Collection
    {
        return $orderItems->unique(function ($orderItem) {
            return $orderItem->order->buyer_id;
        })->map(function ($item) {
            return $item->order->buyer;
        });
    }
}
