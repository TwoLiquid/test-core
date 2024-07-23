<?php

namespace App\Services\User;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Services\User\Interfaces\UserUnsuspendRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserUnsuspendRequestService
 *
 * @package App\Services\User
 */
class UserUnsuspendRequestService implements UserUnsuspendRequestServiceInterface
{
    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * UserUnsuspendRequestService constructor
     */
    public function __construct()
    {
        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();
    }

    /**
     * @param Collection|null $userUnsuspendRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $userUnsuspendRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking user unsuspend requests existence
             */
            if ($userUnsuspendRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->userUnsuspendRequestRepository->getRequestStatusCountByIds(
                    $userUnsuspendRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->userUnsuspendRequestRepository->getRequestStatusCount(
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
