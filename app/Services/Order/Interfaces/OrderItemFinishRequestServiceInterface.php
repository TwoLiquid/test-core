<?php

namespace App\Services\Order\Interfaces;

use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemFinishRequest;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrderItemFinishRequestServiceInterface
 *
 * @package App\Services\Order\Interfaces
 */
interface OrderItemFinishRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return bool
     */
    public function isOpened(
        OrderItemFinishRequest $orderItemFinishRequest
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection|null $orderItemFinishRequests
     *
     * @return Collection
     */
    public function getForAdminStatusesWithCounts(
        ?Collection $orderItemFinishRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection|null $orderItemFinishRequests
     *
     * @return Collection
     */
    public function getForAdminToOrderItemStatusesWithCounts(
        ?Collection $orderItemFinishRequests
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param OrderItemFinishRequest $orderItemFinishRequest
     * @param OrderItemStatusListItem $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemFinishRequest
     */
    public function close(
        User $user,
        OrderItemFinishRequest $orderItemFinishRequest,
        OrderItemStatusListItem $toOrderItemStatusListItem,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemFinishRequest;
}