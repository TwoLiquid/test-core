<?php

namespace App\Services\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Services\Vybe\Interfaces\VybeDeletionRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeDeletionRequestService
 *
 * @package App\Services\Vybe
 */
class VybeDeletionRequestService implements VybeDeletionRequestServiceInterface
{
    /**
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * VybeDeletionRequestService constructor
     */
    public function __construct()
    {
        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();
    }

    /**
     * @param Collection|null $vybeDeletionRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeDeletionRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking vybe deletion requests existence
             */
            if ($vybeDeletionRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeDeletionRequestRepository->getRequestStatusCountByIds(
                    $vybeDeletionRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeDeletionRequestRepository->getRequestStatusCount(
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
