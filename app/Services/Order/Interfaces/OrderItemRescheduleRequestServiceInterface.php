<?php

namespace App\Services\Order\Interfaces;

use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemRescheduleRequest;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrderItemRescheduleRequestServiceInterface
 *
 * @package App\Services\Order\Interfaces
 */
interface OrderItemRescheduleRequestServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     *
     * @return bool
     */
    public function isOpened(
        OrderItemRescheduleRequest $orderItemRescheduleRequest
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection|null $orderItemRescheduleRequests
     *
     * @return Collection
     */
    public function getForAdminStatusesWithCounts(
        ?Collection $orderItemRescheduleRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection|null $orderItemRescheduleRequests
     *
     * @return Collection
     */
    public function getForAdminToOrderItemStatusesWithCounts(
        ?Collection $orderItemRescheduleRequests
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param OrderItemRescheduleRequest $orderItemRescheduleRequest
     * @param OrderItemStatusListItem $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemRescheduleRequest
     */
    public function close(
        User $user,
        OrderItemRescheduleRequest $orderItemRescheduleRequest,
        OrderItemStatusListItem $toOrderItemStatusListItem,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemRescheduleRequest;
}