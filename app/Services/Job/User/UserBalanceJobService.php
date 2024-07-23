<?php

namespace App\Services\Job\User;

use App\Exceptions\DatabaseException;
use App\Jobs\User\Balance\PendingPayoutJob;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use App\Services\Job\User\Interfaces\UserBalanceJobServiceInterface;
use App\Settings\User\PendingPayoutDelaySetting;
use Carbon\Carbon;

/**
 * Class UserBalanceJobService
 *
 * @package App\Services\Job\User
 */
class UserBalanceJobService implements UserBalanceJobServiceInterface
{
    /**
     * @var PendingPayoutDelaySetting
     */
    protected PendingPayoutDelaySetting $pendingPayoutDelaySetting;

    /**
     * UserBalanceJobService constructor
     */
    public function __construct()
    {
        /** @var PendingPayoutDelaySetting pendingPayoutDelaySetting */
        $this->pendingPayoutDelaySetting = new PendingPayoutDelaySetting();
    }

    /**
     * @param User $user
     * @param OrderItem $orderItem
     *
     * @throws DatabaseException
     */
    public function pendingPayout(
        User $user,
        OrderItem $orderItem
    ) : void
    {
        /**
         * Setting up user
         */
        $this->pendingPayoutDelaySetting->setUser(
            $user
        );

        /**
         * Dispatching job
         */
        PendingPayoutJob::dispatch([
            'balance_id' => $user->getSellerBalance()->id,
            'amount'     => round(
                $orderItem->amount_earned * $orderItem->quantity,
                2
            )
        ])->delay(
            Carbon::now()->addHours(
                $this->pendingPayoutDelaySetting
                    ->getHours()
            )
        );
    }
}