<?php

namespace App\Services\User;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Services\User\Interfaces\UserDeletionRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserDeletionRequestService
 *
 * @package App\Services\User
 */
class UserDeletionRequestService implements UserDeletionRequestServiceInterface
{
    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * UserDeletionRequestService constructor
     */
    public function __construct()
    {
        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();
    }

    /**
     * @param Collection|null $userDeletionRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $userDeletionRequests = null
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking user deactivation requests
             */
            if ($userDeletionRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->userDeletionRequestRepository->getRequestStatusCountByIds(
                    $userDeletionRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->userDeletionRequestRepository->getRequestStatusCount(
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
