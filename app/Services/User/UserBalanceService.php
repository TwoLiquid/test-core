<?php

namespace App\Services\User;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MongoDb\User\Billing\BillingChangeRequest;
use App\Models\MongoDb\WithdrawalRequest;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserBalance;
use App\Repositories\User\UserBalanceRepository;
use App\Services\User\Interfaces\UserBalanceServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserBalanceService
 *
 * @package App\Services\User
 */
class UserBalanceService implements UserBalanceServiceInterface
{
    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * UserBalanceService constructor
     */
    public function __construct()
    {
        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();
    }

    /**
     * @param User $user
     * @param float $amount
     *
     * @return bool
     */
    public function isBuyerBalanceEnough(
        User $user,
        float $amount
    ) : bool
    {
        /**
         * Checking user-buyer balance
         */
        if ($user->getBuyerBalance()->amount >= $amount) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection $withdrawalRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getTypesCountsByWithdrawalRequestsIds(
        Collection $withdrawalRequests
    ) : Collection
    {
        $userBalancesIds = [];

        /** @var WithdrawalRequest $withdrawalRequest */
        foreach ($withdrawalRequests as $withdrawalRequest) {

            /** @var UserBalance $userBalance */
            foreach ($withdrawalRequest->user->balances as $userBalance) {
                if (!in_array($userBalance->id, $userBalancesIds)) {
                    $userBalancesIds[] = $userBalance->id;
                }
            }
        }

        /**
         * Getting user balance accounts count
         */
        $userBalanceAccountsCounts = $this->userBalanceRepository->getByIdsTypesCount(
            $userBalancesIds
        );

        /**
         * Getting a user balance types
         */
        $userBalanceTypes = UserBalanceTypeList::getItems();

        /** @var UserBalanceTypeListItem $userBalanceType */
        foreach ($userBalanceTypes as $userBalanceType) {

            /**
             * Setting user balance types count
             */
            $userBalanceType->setCount(
                $userBalanceAccountsCounts->getAttribute(
                    $userBalanceType->code
                )
            );
        }

        return $userBalanceTypes;
    }

    /**
     * @param Collection $payoutMethodRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getTypesCountsByPayoutMethodRequestsIds(
        Collection $payoutMethodRequests
    ) : Collection
    {
        $userBalancesIds = [];

        /** @var PayoutMethodRequest $payoutMethodRequest */
        foreach ($payoutMethodRequests as $payoutMethodRequest) {

            /** @var UserBalance $userBalance */
            foreach ($payoutMethodRequest->user->balances as $userBalance) {
                if (!in_array($userBalance->id, $userBalancesIds)) {
                    $userBalancesIds[] = $userBalance->id;
                }
            }
        }

        /**
         * Getting user balance accounts count
         */
        $userBalanceAccountsCounts = $this->userBalanceRepository->getByIdsTypesCount(
            $userBalancesIds
        );

        /**
         * Getting a user balance types
         */
        $userBalanceTypes = UserBalanceTypeList::getItems();

        /** @var UserBalanceTypeListItem $userBalanceType */
        foreach ($userBalanceTypes as $userBalanceType) {

            /**
             * Setting user balance types count
             */
            $userBalanceType->setCount(
                $userBalanceAccountsCounts->getAttribute(
                    $userBalanceType->code
                )
            );
        }

        return $userBalanceTypes;
    }

    /**
     * @param Collection $billingChangeRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getTypesCountsByBillingChangeRequestsIds(
        Collection $billingChangeRequests
    ) : Collection
    {
        $userBalancesIds = [];

        /** @var BillingChangeRequest $billingChangeRequest */
        foreach ($billingChangeRequests as $billingChangeRequest) {

            /** @var UserBalance $userBalance */
            foreach ($billingChangeRequest->user->balances as $userBalance) {
                if (!in_array($userBalance->id, $userBalancesIds)) {
                    $userBalancesIds[] = $userBalance->id;
                }
            }
        }

        /**
         * Getting user balance accounts count
         */
        $userBalanceAccountsCounts = $this->userBalanceRepository->getByIdsTypesCount(
            $userBalancesIds
        );

        /**
         * Getting a user balance types
         */
        $userBalanceTypes = UserBalanceTypeList::getItems();

        /** @var UserBalanceTypeListItem $userBalanceType */
        foreach ($userBalanceTypes as $userBalanceType) {

            /**
             * Setting user balance types count
             */
            $userBalanceType->setCount(
                $userBalanceAccountsCounts->getAttribute(
                    $userBalanceType->code
                )
            );
        }

        return $userBalanceTypes;
    }

    /**
     * @param UserBalance $userBalance
     * @param float $amount
     * @param bool $decrease
     * @param bool $pending
     *
     * @return UserBalance
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function change(
        UserBalance $userBalance,
        float $amount,
        bool $decrease = false,
        bool $pending = false
    ) : UserBalance
    {
        /**
         * Checking decrease
         */
        if ($decrease === false) {

            /**
             * Checking pending
             */
            if ($pending === false) {

                /**
                 * Updating user balance
                 */
                $userBalance = $this->userBalanceRepository->increaseAmount(
                    $userBalance,
                    $amount
                );
            } else {

                /**
                 * Checking a user balance type
                 */
                if (!$userBalance->getType()->isBuyer()) {

                    /**
                     * Updating user balance
                     */
                    $userBalance = $this->userBalanceRepository->increasePendingAmount(
                        $userBalance,
                        $amount
                    );
                } else {
                    throw new BaseException(
                        'Buyer has no pending balance.',
                        null,
                        400
                    );
                }
            }
        } else {

            /**
             * Checking pending
             */
            if ($pending === false) {

                /**
                 * Updating user balance
                 */
                $userBalance = $this->userBalanceRepository->decreaseAmount(
                    $userBalance,
                    $amount
                );
            } else {

                /**
                 * Checking a user balance type
                 */
                if (!$userBalance->getType()->isBuyer()) {

                    /**
                     * Updating user balance
                     */
                    $userBalance = $this->userBalanceRepository->decreasePendingAmount(
                        $userBalance,
                        $amount
                    );
                } else {
                    throw new BaseException(
                        'Buyer has no pending balance.',
                        null,
                        400
                    );
                }
            }
        }

        return $userBalance;
    }

    /**
     * @param User $user
     * @param float $amount
     *
     * @return UserBalance
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function pendingPayout(
        User $user,
        float $amount
    ) : UserBalance
    {
        /**
         * Decreasing user seller pending balance
         */
        $this->change(
            $user->getSellerBalance(),
            $amount,
            true,
            true
        );

        /**
         * Increasing user-seller balance
         */
        return $this->change(
            $user->getSellerBalance(),
            $amount
        );
    }
}
