<?php

namespace App\Services\Sale;

use App\Exceptions\DatabaseException;
use App\Exceptions\LogException;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Sale;
use App\Repositories\Sale\SaleRepository;
use App\Repositories\User\UserRepository;
use App\Services\Log\LogService;
use App\Services\Order\OrderItemService;
use App\Services\Sale\Interfaces\SaleServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SaleService
 *
 * @package App\Services\Sale
 */
class SaleService implements SaleServiceInterface
{
    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var SaleRepository
     */
    protected SaleRepository $saleRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * SaleService constructor
     */
    public function __construct()
    {
        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var SaleRepository saleRepository */
        $this->saleRepository = new SaleRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param Order $order
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function createForOrder(
        Order $order
    ) : Collection
    {
        /**
         * Preparing sales variable
         */
        $sales = new Collection();

        /**
         * Preparing sellers ids variable
         */
        $sellers = [];

        /** @var OrderItem $orderItem */
        foreach ($order->items as $orderItem) {
            $sellers[$orderItem->seller_id][] = $orderItem;
        }

        /**
         * @var int $sellerId
         * @var array $seller
         */
        foreach ($sellers as $sellerId => $sellerOrderItems) {

            /**
             * Preparing order items variable
             */
            $orderItems = new Collection();

            /** @var OrderItem $sellerOrderItem */
            foreach ($sellerOrderItems as $sellerOrderItem) {

                /**
                 * Add seller order item to a collection
                 */
                $orderItems->add($sellerOrderItem);
            }

            /**
             * Getting sale amount earned
             */
            $amountEarned = $this->orderItemService->getTotalAmountEarned(
                $orderItems
            );

            /**
             * Getting sale amount total
             */
            $amountTotal = $this->orderItemService->getAmountTotal(
                $orderItems
            );

            /**
             * Getting sale handling fee
             */
            $handlingFee = $this->orderItemService->getTotalHandlingFee(
                $orderItems
            );

            /**
             * Getting seller
             */
            $seller = $this->userRepository->findById(
                $sellerId
            );

            /**
             * Creating sale
             */
            $sale = $this->saleRepository->store(
                $order,
                $seller,
                $amountEarned,
                $amountTotal,
                $handlingFee
            );

            /**
             * Checking sale existence
             */
            if ($sale) {

                /**
                 * Attaching sale order items
                 */
                $this->saleRepository->attachOrderItems(
                    $sale,
                    $orderItems->pluck('id')
                        ->values()
                        ->toArray()
                );

                /**
                 * Add sale to a prepared collection
                 */
                $sales->add($sale);
            }
        }

        return $sales;
    }

    /**
     * @param Collection $sales
     * @param string $code
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addTransactionLogs(
        Collection $sales,
        string $code
    ) : void
    {
        /** @var Sale $sale */
        foreach ($sales as $sale) {

            /**
             * Creating sale overview log
             */
            $this->logService->addSaleOverviewLog(
                $sale,
                $sale->seller->getSellerBalance(),
                UserBalanceTypeList::getSeller(),
                $code
            );
        }
    }
}
