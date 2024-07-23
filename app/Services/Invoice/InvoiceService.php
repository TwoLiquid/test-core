<?php

namespace App\Services\Invoice;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\LogException;
use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Invoice\Interfaces\InvoiceServiceInterface;
use App\Services\Log\LogService;
use App\Services\Order\OrderItemService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class InvoiceService
 *
 * @package App\Services\Invoice
 */
class InvoiceService implements InvoiceServiceInterface
{
    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var OrderInvoiceRepository
     */
    protected OrderInvoiceRepository $orderInvoiceRepository;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * InvoiceService constructor
     */
    public function __construct()
    {
        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var OrderInvoiceRepository orderInvoiceRepository */
        $this->orderInvoiceRepository = new OrderInvoiceRepository();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param Order $order
     *
     * @return OrderInvoice
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function createForBuyer(
        Order $order
    ) : OrderInvoice
    {
        /**
         * Creating invoice
         */
        $orderInvoice = $this->orderInvoiceRepository->store(
            null,
            $order,
            InvoiceTypeList::getBuyer(),
            InvoiceStatusList::getUnpaid()
        );

        if (!$orderInvoice) {
            throw new BaseException(
                trans('exceptions/service/invoice.' . __FUNCTION__ . '.invoiceStore'),
                null,
                500
            );
        }

        /**
         * Getting order items ids
         */
        $orderItemsIds = $order->items
            ->pluck('id')
            ->toArray();

        /**
         * Attaching order items
         */
        $this->orderInvoiceRepository->attachOrderItems(
            $orderInvoice,
            $orderItemsIds
        );

        return $orderInvoice;
    }

    /**
     * @param OrderInvoice $buyerInvoice
     * @param OrderItem $orderItem
     *
     * @return OrderInvoice
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function createCreditForBuyer(
        OrderInvoice $buyerInvoice,
        OrderItem $orderItem
    ) : OrderInvoice
    {
        /**
         * Creating order invoice
         */
        $orderInvoice = $this->orderInvoiceRepository->store(
            $buyerInvoice,
            $orderItem->order,
            InvoiceTypeList::getCreditBuyer(),
            InvoiceStatusList::getCredit()
        );

        /**
         * Checking order invoice existence
         */
        if (!$orderInvoice) {
            throw new BaseException(
                trans('exceptions/service/invoice.' . __FUNCTION__ . '.invoice.create'),
                null,
                500
            );
        }

        /**
         * Attaching order item
         */
        $this->orderInvoiceRepository->attachOrderItem(
            $orderInvoice,
            $orderItem
        );

        return $orderInvoice;
    }

    /**
     * @param Order $order
     *
     * @return Collection
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function createForSellers(
        Order $order
    ) : Collection
    {
        /**
         * Preparing an invoice array
         */
        $invoices = new Collection();

        /** @var OrderItem $orderItem */
        foreach ($order->items as $orderItem) {

            /**
             * Creating seller invoice for
             */
            $orderInvoice = $this->orderInvoiceRepository->store(
                null,
                $order,
                InvoiceTypeList::getSeller(),
                InvoiceStatusList::getOnHold()
            );

            /**
             * Checking seller invoice existence
             */
            if (!$orderInvoice) {
                throw new BaseException(
                    trans('exceptions/service/invoice.' . __FUNCTION__ . '.invoiceStore'),
                    null,
                    500
                );
            }

            /**
             * Attaching order item
             */
            $this->orderInvoiceRepository->attachOrderItem(
                $orderInvoice,
                $orderItem
            );

            /**
             * Adding invoice to a response array
             */
            $invoices->add($orderInvoice);
        }

        return $invoices;
    }

    /**
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForBuyerFromOrder(
        Collection $orderInvoices
    ) : Collection
    {
        /**
         * Preparing invoices for a buyer
         */
        $invoicesForBuyer = new Collection();

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {

            /**
             * Checking an invoice type attachment
             */
            if ($orderInvoice->getType()->attachment == 'buyer') {

                /**
                 * Adding invoice
                 */
                $invoicesForBuyer->add(
                    $orderInvoice
                );
            }
        }

        return $invoicesForBuyer;
    }

    /**
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForSellerFromOrder(
        Collection $orderInvoices
    ) : Collection
    {
        /**
         * Preparing invoices for seller
         */
        $invoicesForSeller = new Collection();

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {

            /**
             * Checking an invoice type attachment
             */
            if ($orderInvoice->getType()->attachment == 'seller') {

                /**
                 * Adding invoice
                 */
                $invoicesForSeller->add(
                    $orderInvoice
                );
            }
        }

        return $invoicesForSeller;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Collection
     */
    public function getUniqueStatusesFromOrderItem(
        OrderItem $orderItem
    ) : Collection
    {
        $invoiceStatusesIds = [];

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderItem->invoices as $orderInvoice) {
            if (!in_array($orderInvoice->status_id, $invoiceStatusesIds)) {
                $invoiceStatusesIds[] = $orderInvoice->status_id;
            }
        }

        return InvoiceStatusList::getItemsByIds(
            $invoiceStatusesIds
        );
    }

    /**
     * @param Collection $orderInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminBuyerStatusesByIds(
        Collection $orderInvoices
    ) : Collection
    {
        $orderInvoicesIds = [];

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {
            if (!in_array($orderInvoice->id, $orderInvoicesIds)) {
                $orderInvoicesIds[] = $orderInvoice->id;
            }
        }

        /**
         * Getting order item statuses count
         */
        $orderInvoiceStatusesCounts = $this->orderInvoiceRepository->getForBuyerStatusesByIdsCount(
            $orderInvoicesIds
        );

        /**
         * Getting order items statuses
         */
        $orderInvoiceStatuses = InvoiceStatusList::getAllForBuyer();

        /** @var InvoiceStatusListItem $orderInvoiceStatus */
        foreach ($orderInvoiceStatuses as $orderInvoiceStatus) {

            /**
             * Setting order item statuses count
             */
            $orderInvoiceStatus->setCount(
                $orderInvoiceStatusesCounts->getAttribute(
                    $orderInvoiceStatus->code
                )
            );
        }

        return $orderInvoiceStatuses;
    }

    /**
     * @param Collection $orderInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminSellerStatusesByIds(
        Collection $orderInvoices
    ) : Collection
    {
        $orderInvoicesIds = [];

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {
            if (!in_array($orderInvoice->id, $orderInvoicesIds)) {
                $orderInvoicesIds[] = $orderInvoice->id;
            }
        }

        /**
         * Getting order item statuses count
         */
        $orderInvoiceStatusesCounts = $this->orderInvoiceRepository->getForSellerStatusesByIdsCount(
            $orderInvoicesIds
        );

        /**
         * Getting order items statuses
         */
        $orderInvoiceStatuses = InvoiceStatusList::getAllForSeller();

        /** @var InvoiceStatusListItem $orderInvoiceStatus */
        foreach ($orderInvoiceStatuses as $orderInvoiceStatus) {

            /**
             * Setting order item statuses count
             */
            $orderInvoiceStatus->setCount(
                $orderInvoiceStatusesCounts->getAttribute(
                    $orderInvoiceStatus->code
                )
            );
        }

        return $orderInvoiceStatuses;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByOrderItemsForAdminSellerStatusesByIds(
        Collection $orderItems
    ) : Collection
    {
        $orderInvoicesIds = [];

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            /** @var OrderInvoice $orderInvoice */
            foreach ($orderItem->order->invoices as $orderInvoice) {
                if (!in_array($orderInvoice->id, $orderInvoicesIds)) {
                    $orderInvoicesIds[] = $orderInvoice->id;
                }
            }
        }

        /**
         * Getting order item statuses count
         */
        $orderInvoiceStatusesCounts = $this->orderInvoiceRepository->getForSellerStatusesByIdsCount(
            $orderInvoicesIds
        );

        /**
         * Getting order items statuses
         */
        $orderInvoiceStatuses = InvoiceStatusList::getAllForSeller();

        /** @var InvoiceStatusListItem $orderInvoiceStatus */
        foreach ($orderInvoiceStatuses as $orderInvoiceStatus) {

            /**
             * Setting order item statuses count
             */
            $orderInvoiceStatus->setCount(
                $orderInvoiceStatusesCounts->getAttribute(
                    $orderInvoiceStatus->code
                )
            );
        }

        return $orderInvoiceStatuses;
    }

    /**
     * @param User $user
     * @param OrderInvoice $orderInvoice
     *
     * @return bool
     */
    public function belongsToSeller(
        User $user,
        OrderInvoice $orderInvoice
    ) : bool
    {
        if ($orderInvoice->relationLoaded('items')) {

            /** @var OrderItem $orderItem */
            foreach ($orderInvoice->items as $orderItem) {
                if ($orderItem->seller_id == $user->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param Collection $orderInvoices
     * @param string $code
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addSellerTransactionLogs(
        Collection $orderInvoices,
        string $code
    ) : void
    {
        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {

            /**
             * Checking order an invoice type
             */
            if ($orderInvoice->getType()->isSeller()) {

                /**
                 * Getting order invoice sale
                 */
                $sale = $orderInvoice->getSale();

                /**
                 * Creating order invoice for seller log
                 */
                $this->logService->addInvoiceForSellerLog(
                    $sale,
                    $orderInvoice,
                    $sale->seller->getSellerBalance(),
                    UserBalanceTypeList::getSeller(),
                    $code
                );
            }
        }
    }
}
