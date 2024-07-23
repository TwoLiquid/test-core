<?php

namespace App\Services\Order;

use App\Exceptions\DatabaseException;
use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusListItem;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemFinishRequest;
use App\Models\MySql\User\User;
use App\Repositories\Order\OrderItemFinishRequestRepository;
use App\Services\Order\Interfaces\OrderItemFinishRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemFinishRequestService
 *
 * @package App\Services\Order
 */
class OrderItemFinishRequestService implements OrderItemFinishRequestServiceInterface
{
    /**
     * @var OrderItemFinishRequestRepository
     */
    protected OrderItemFinishRequestRepository $orderItemFinishRequestRepository;

    /**
     * OrderItemFinishRequestService constructor
     */
    public function __construct()
    {
        /** @var OrderItemFinishRequestRepository orderItemFinishRequestRepository */
        $this->orderItemFinishRequestRepository = new OrderItemFinishRequestRepository();
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return bool
     */
    public function isOpened(
        OrderItemFinishRequest $orderItemFinishRequest
    ) : bool
    {
        /**
         * Checking order item finish request fields
         */
        if (is_null($orderItemFinishRequest->to_order_item_status_id) &&
            is_null($orderItemFinishRequest->action_id) &&
            is_null($orderItemFinishRequest->closing_id)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection|null $orderItemFinishRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesWithCounts(
        ?Collection $orderItemFinishRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var OrderItemRequestStatusListItem $orderItemRequestStatusListItem */
        foreach (OrderItemRequestStatusList::getItems() as $orderItemRequestStatusListItem) {

            /**
             * Checking order item finish requests
             */
            if ($orderItemFinishRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->orderItemFinishRequestRepository->getRequestStatusCountByIds(
                    $orderItemFinishRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $orderItemRequestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->orderItemFinishRequestRepository->getRequestStatusCount(
                    $orderItemRequestStatusListItem
                );
            }

            /*
             * Setting count
             */
            $orderItemRequestStatusListItem->setCount($count);

            /**
             * Adding request status to a response collection
             */
            $requestStatuses->add($orderItemRequestStatusListItem);
        }

        return $requestStatuses;
    }

    /**
     * @param Collection|null $orderItemFinishRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminToOrderItemStatusesWithCounts(
        ?Collection $orderItemFinishRequests
    ) : Collection
    {
        $orderItemStatuses = new Collection();

        /** @var OrderItemStatusListItem $orderItemStatusListItem */
        foreach (OrderItemStatusList::getItems() as $orderItemStatusListItem) {

            /**
             * Checking order item finish requests
             */
            if ($orderItemFinishRequests) {

                /**
                 * Getting order item status count
                 */
                $count = $this->orderItemFinishRequestRepository->getToOrderItemStatusCountByIds(
                    $orderItemFinishRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $orderItemStatusListItem
                );
            } else {

                /**
                 * Getting order item status count
                 */
                $count = $this->orderItemFinishRequestRepository->getToOrderItemStatusCount(
                    $orderItemStatusListItem
                );
            }

            /*
             * Setting count
             */
            $orderItemStatusListItem->setCount($count);

            /**
             * Adding order item status to a response collection
             */
            $orderItemStatuses->add($orderItemStatusListItem);
        }

        return $orderItemStatuses;
    }

    /**
     * @param User $user
     * @param OrderItemFinishRequest $orderItemFinishRequest
     * @param OrderItemStatusListItem $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemFinishRequest
     *
     * @throws DatabaseException
     */
    public function close(
        User $user,
        OrderItemFinishRequest $orderItemFinishRequest,
        OrderItemStatusListItem $toOrderItemStatusListItem,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemFinishRequest
    {
        /**
         * Checking order item finish request is opened
         */
        if ($this->isOpened($orderItemFinishRequest)) {

            /**
             * Updating order item finish request
             */
            $orderItemFinishRequest = $this->orderItemFinishRequestRepository->updateWhenClose(
                $orderItemFinishRequest,
                $user,
                $toOrderItemStatusListItem,
                $orderItemRequestActionListItem
            );
        }

        return $orderItemFinishRequest;
    }
}
