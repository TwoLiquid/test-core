<?php

namespace App\Services\Order\Interfaces;

use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemPendingRequest;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrderItemPendingRequestServiceInterface
 *
 * @package App\Services\Order\Interfaces
 */
interface OrderItemPendingRequestServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param OrderItemPendingRequest $orderItemPendingRequest
     *
     * @return bool
     */
    public function isOpened(
        OrderItemPendingRequest $orderItemPendingRequest
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection|null $orderItemPendingRequests
     *
     * @return Collection
     */
    public function getForAdminStatusesWithCounts(
        ?Collection $orderItemPendingRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection|null $orderItemPendingRequests
     *
     * @return Collection
     */
    public function getForAdminToOrderItemStatusesWithCounts(
        ?Collection $orderItemPendingRequests
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param OrderItemPendingRequest $orderItemPendingRequest
     * @param OrderItemStatusListItem $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemPendingRequest
     */
    public function close(
        User $user,
        OrderItemPendingRequest $orderItemPendingRequest,
        OrderItemStatusListItem $toOrderItemStatusListItem,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemPendingRequest;
}