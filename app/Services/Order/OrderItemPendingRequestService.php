<?php

namespace App\Services\Order;

use App\Exceptions\DatabaseException;
use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusListItem;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemPendingRequest;
use App\Models\MySql\User\User;
use App\Repositories\Order\OrderItemPendingRequestRepository;
use App\Services\Order\Interfaces\OrderItemPendingRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemPendingRequestService
 *
 * @package App\Services\Order
 */
class OrderItemPendingRequestService implements OrderItemPendingRequestServiceInterface
{
    /**
     * @var OrderItemPendingRequestRepository
     */
    protected OrderItemPendingRequestRepository $orderItemPendingRequestRepository;

    /**
     * OrderItemPendingRequestService constructor
     */
    public function __construct()
    {
        /** @var OrderItemPendingRequestRepository orderItemPendingRequestRepository */
        $this->orderItemPendingRequestRepository = new OrderItemPendingRequestRepository();
    }

    /**
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return bool
     */
    public function isOpened(
        OrderItemPendingRequest $orderItemPendingRequest
    ) : bool
    {
        /**
         * Checking order item pending request fields
         */
        if (is_null($orderItemPendingRequest->to_order_item_status_id) &&
            is_null($orderItemPendingRequest->action_id) &&
            is_null($orderItemPendingRequest->closing_id)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection|null $orderItemPendingRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesWithCounts(
        ?Collection $orderItemPendingRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var OrderItemRequestStatusListItem $orderItemRequestStatusListItem */
        foreach (OrderItemRequestStatusList::getItems() as $orderItemRequestStatusListItem) {

            /**
             * Checking order item pending requests
             */
            if ($orderItemPendingRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->orderItemPendingRequestRepository->getRequestStatusCountByIds(
                    $orderItemPendingRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $orderItemRequestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->orderItemPendingRequestRepository->getRequestStatusCount(
                    $orderItemRequestStatusListItem
                );
            }

            /**
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
     * @param Collection|null $orderItemPendingRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminToOrderItemStatusesWithCounts(
        ?Collection $orderItemPendingRequests
    ) : Collection
    {
        $orderItemStatuses = new Collection();

        /** @var OrderItemStatusListItem $orderItemStatusListItem */
        foreach (OrderItemStatusList::getItems() as $orderItemStatusListItem) {

            /**
             * Checking order item pending requests
             */
            if ($orderItemPendingRequests) {

                /**
                 * Getting order item status count
                 */
                $count = $this->orderItemPendingRequestRepository->getToOrderItemStatusCountByIds(
                    $orderItemPendingRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $orderItemStatusListItem
                );
            } else {

                /**
                 * Getting order item status count
                 */
                $count = $this->orderItemPendingRequestRepository->getToOrderItemStatusCount(
                    $orderItemStatusListItem
                );
            }

            /**
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
     * @param OrderItemPendingRequest $orderItemPendingRequest
     * @param OrderItemStatusListItem $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemPendingRequest
     *
     * @throws DatabaseException
     */
    public function close(
        User $user,
        OrderItemPendingRequest $orderItemPendingRequest,
        OrderItemStatusListItem $toOrderItemStatusListItem,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemPendingRequest
    {
        /**
         * Checking order item pending request is opened
         */
        if ($this->isOpened($orderItemPendingRequest)) {

            /**
             * Updating order item pending request
             */
            $orderItemPendingRequest = $this->orderItemPendingRequestRepository->updateWhenClose(
                $orderItemPendingRequest,
                $user,
                $toOrderItemStatusListItem,
                $orderItemRequestActionListItem
            );
        }

        return $orderItemPendingRequest;
    }
}
