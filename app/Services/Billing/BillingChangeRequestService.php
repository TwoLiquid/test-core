<?php

namespace App\Services\Billing;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Services\Billing\Interfaces\BillingChangeRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BillingChangeRequestService
 *
 * @package App\Services\Billing
 */
class BillingChangeRequestService implements BillingChangeRequestServiceInterface
{
    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * BillingChangeRequestService constructor
     */
    public function __construct()
    {
        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();
    }

    /**
     * @param Collection|null $billingChangeRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $billingChangeRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking billing change requests existence
             */
            if ($billingChangeRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->billingChangeRequestRepository->getRequestStatusCountByIds(
                    $billingChangeRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->billingChangeRequestRepository->getRequestStatusCount(
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
             * Setting buyers billing change requests count
             */
            if ($userBalanceTypeListItem->isBuyer()) {
                $userBalanceTypeListItem->setCount(
                    $this->billingChangeRequestRepository->getAllBuyersCount()
                );
            }

            /**
             * Setting sellers billing change requests count
             */
            if ($userBalanceTypeListItem->isSeller()) {
                $userBalanceTypeListItem->setCount(
                    $this->billingChangeRequestRepository->getAllSellersCount()
                );
            }

            /**
             * Setting affiliates billing change requests count
             */
            if ($userBalanceTypeListItem->isAffiliate()) {
                $userBalanceTypeListItem->setCount(
                    $this->billingChangeRequestRepository->getAllAffiliatesCount()
                );
            }
        }

        return $userBalanceTypeListItems;
    }
}
