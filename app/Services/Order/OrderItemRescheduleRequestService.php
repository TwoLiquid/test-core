<?php

namespace App\Services\Order;

use App\Exceptions\DatabaseException;
use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusListItem;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemRescheduleRequest;
use App\Models\MySql\User\User;
use App\Repositories\Order\OrderItemRescheduleRequestRepository;
use App\Services\Order\Interfaces\OrderItemRescheduleRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemRescheduleRequestService
 *
 * @package App\Services\Order
 */
class OrderItemRescheduleRequestService implements OrderItemRescheduleRequestServiceInterface
{
    /**
     * @var OrderItemRescheduleRequestRepository
     */
    protected OrderItemRescheduleRequestRepository $orderItemRescheduleRequestRepository;

    /**
     * OrderItemRescheduleRequestService constructor
     */
    public function __construct()
    {
        /** @var OrderItemRescheduleRequestRepository orderItemRescheduleRequestRepository */
        $this->orderItemRescheduleRequestRepository = new OrderItemRescheduleRequestRepository();
    }

    /**
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return bool
     */
    public function isOpened(
        OrderItemRescheduleRequest $orderItemRescheduleRequest
    ) : bool
    {
        /**
         * Checking order item reschedule request fields
         */
        if (is_null($orderItemRescheduleRequest->to_order_item_status_id) &&
            is_null($orderItemRescheduleRequest->action_id) &&
            is_null($orderItemRescheduleRequest->closing_id)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection|null $orderItemRescheduleRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesWithCounts(
        ?Collection $orderItemRescheduleRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var OrderItemRequestStatusListItem $orderItemRequestStatusListItem */
        foreach (OrderItemRequestStatusList::getItems() as $orderItemRequestStatusListItem) {

            /**
             * Checking order item reschedule requests
             */
            if ($orderItemRescheduleRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->orderItemRescheduleRequestRepository->getRequestStatusCountByIds(
                    $orderItemRescheduleRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $orderItemRequestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->orderItemRescheduleRequestRepository->getRequestStatusCount(
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
     * @param Collection|null $orderItemRescheduleRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminToOrderItemStatusesWithCounts(
        ?Collection $orderItemRescheduleRequests
    ) : Collection
    {
        $orderItemStatuses = new Collection();

        /** @var OrderItemStatusListItem $orderItemStatusListItem */
        foreach (OrderItemStatusList::getItems() as $orderItemStatusListItem) {

            /**
             * Checking order item reschedule requests
             */
            if ($orderItemRescheduleRequests) {

                /**
                 * Getting order item status count
                 */
                $count = $this->orderItemRescheduleRequestRepository->getToOrderItemStatusCountByIds(
                    $orderItemRescheduleRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $orderItemStatusListItem
                );
            } else {

                /**
                 * Getting order item status count
                 */
                $count = $this->orderItemRescheduleRequestRepository->getToOrderItemStatusCount(
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
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     * @param OrderItemStatusListItem $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemRescheduleRequest
     *
     * @throws DatabaseException
     */
    public function close(
        User $user,
        OrderItemRescheduleRequest $orderItemRescheduleRequest,
        OrderItemStatusListItem $toOrderItemStatusListItem,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemRescheduleRequest
    {
        /**
         * Checking order item reschedule request is opened
         */
        if ($this->isOpened($orderItemRescheduleRequest)) {

            /**
             * Updating order item reschedule request
             */
            $orderItemRescheduleRequest = $this->orderItemRescheduleRequestRepository->updateWhenClose(
                $orderItemRescheduleRequest,
                $user,
                $toOrderItemStatusListItem,
                $orderItemRequestActionListItem
            );
        }

        return $orderItemRescheduleRequest;
    }
}
