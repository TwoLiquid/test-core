<?php

namespace App\Jobs\User\Balance;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Repositories\User\UserBalanceRepository;
use App\Services\User\UserBalanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class PendingPayoutJob
 *
 * @package App\Jobs\User\Balance
 */
class PendingPayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserBalanceService
     */
    protected UserBalanceService $userBalanceService;

    /**
     * SellerPendingBalanceJob constructor
     *
     * @param array $parameters
     */
    public function __construct(
        array $parameters
    )
    {
        /** @var array parameters */
        $this->parameters = $parameters;

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var UserBalanceService userBalanceService */
        $this->userBalanceService = new UserBalanceService();
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function handle() : void
    {
        /**
         * Getting user balance
         */
        $userBalance = $this->userBalanceRepository->findById(
            $this->parameters['balance_id']
        );

        /**
         * Checking user balance existence
         */
        if ($userBalance) {

            /**
             * Updating user balance
             */
            $this->userBalanceService->pendingPayout(
                $userBalance->user,
                $this->parameters['amount']
            );
        }
    }
}
