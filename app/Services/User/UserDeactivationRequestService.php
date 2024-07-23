<?php

namespace App\Services\User;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Services\User\Interfaces\UserDeactivationRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserDeactivationRequestService
 *
 * @package App\Services\User
 */
class UserDeactivationRequestService implements UserDeactivationRequestServiceInterface
{
    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * UserDeactivationRequestService constructor
     */
    public function __construct()
    {
        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();
    }

    /**
     * @param Collection|null $userDeactivationRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $userDeactivationRequests = null
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking user deactivation requests
             */
            if ($userDeactivationRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->userDeactivationRequestRepository->getRequestStatusCountByIds(
                    $userDeactivationRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->userDeactivationRequestRepository->getRequestStatusCount(
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
}
