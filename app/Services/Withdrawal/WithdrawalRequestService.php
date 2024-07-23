<?php

namespace App\Services\Withdrawal;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Models\MongoDb\WithdrawalRequest;
use App\Models\MySql\User\User;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Services\Withdrawal\Interfaces\WithdrawalRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class WithdrawalRequestService
 *
 * @package App\Services\Withdrawal
 */
class WithdrawalRequestService implements WithdrawalRequestServiceInterface
{
    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * WithdrawalRequestService constructor
     */
    public function __construct()
    {
        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();
    }

    /**
     * @param User $user
     * @param int $amount
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function isAvailableForWithdrawal(
        User $user,
        int $amount
    ) : bool
    {
        /**
         * Preparing withdrawal involved amount
         */
        $involvedCount = 0;

        /**
         * Getting user pending withdrawal requests
         */
        $pendingWithdrawalRequests = $this->withdrawalRequestRepository->getPendingForUser(
            $user
        );

        /** @var WithdrawalRequest $pendingWithdrawalRequest */
        foreach ($pendingWithdrawalRequests as $pendingWithdrawalRequest) {
            $involvedCount = $involvedCount + $pendingWithdrawalRequest->amount;
        }

        /**
         * Checking is available to make another withdrawal request
         */
        if ($user->getSellerBalance()->amount < ($amount + $involvedCount)) {
            return false;
        }

        return true;
    }

    /**
     * @param Collection|null $withdrawalRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $withdrawalRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking withdrawal requests existence
             */
            if ($withdrawalRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->withdrawalRequestRepository->getRequestStatusCountByIds(
                    $withdrawalRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->withdrawalRequestRepository->getRequestStatusCount(
                    $requestStatusListItem
                );
            }

            /**
             * Setting count
             */
            $requestStatusListItem->setCount($count);

            /**
             * Adding request status to a response collection
             */
            $requestStatuses->add($requestStatusListItem);
        }

        return $requestStatuses;
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getUserBalanceTypesWithCounts() : Collection
    {
        /**
         * Getting a user balance types
         */
        $userBalanceTypeListItems = UserBalanceTypeList::getItems();

        /** @var UserBalanceTypeListItem $userBalanceTypeListItem */
        foreach ($userBalanceTypeListItems as $userBalanceTypeListItem) {

            /**
             * Setting buyers withdrawal requests count
             */
            if ($userBalanceTypeListItem->isBuyer()) {
                $userBalanceTypeListItem->setCount(
                    $this->withdrawalRequestRepository->getAllBuyersCount()
                );
            }

            /**
             * Setting seller withdrawal requests count
             */
            if ($userBalanceTypeListItem->isSeller()) {
                $userBalanceTypeListItem->setCount(
                    $this->withdrawalRequestRepository->getAllSellersCount()
                );
            }

            /**
             * Setting affiliates withdrawal requests count
             */
            if ($userBalanceTypeListItem->isAffiliate()) {
                $userBalanceTypeListItem->setCount(
                    $this->withdrawalRequestRepository->getAllAffiliatesCount()
                );
            }
        }

        return $userBalanceTypeListItems;
    }
}
