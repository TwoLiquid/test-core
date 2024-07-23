<?php

namespace App\Services\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Services\Vybe\Interfaces\VybeUnsuspendRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeUnsuspendRequestService
 *
 * @package App\Services\Vybe
 */
class VybeUnsuspendRequestService implements VybeUnsuspendRequestServiceInterface
{
    /**
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * VybeUnsuspendRequestService constructor
     */
    public function __construct()
    {
        /** @var VybeUnsuspendRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();
    }

    /**
     * @param Collection|null $vybeUnsuspendRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeUnsuspendRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking vybe unsuspend requests existence
             */
            if ($vybeUnsuspendRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeUnsuspendRequestRepository->getRequestStatusCountByIds(
                    $vybeUnsuspendRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeUnsuspendRequestRepository->getRequestStatusCount(
                    $requestStatusListItem
                );
            }

            /*
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
