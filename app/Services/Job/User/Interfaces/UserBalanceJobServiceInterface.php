<?php

namespace App\Services\Job\User\Interfaces;

use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;

/**
 * Interface UserBalanceJobServiceInterface
 *
 * @package App\Services\Job\User\Interfaces
 */
interface UserBalanceJobServiceInterface
{
    /**
     * This method provides creating a delayed job
     *
     * @param User $user
     * @param OrderItem $orderItem
     */
    public function pendingPayout(
        User $user,
        OrderItem $orderItem
    ) : void;
}
