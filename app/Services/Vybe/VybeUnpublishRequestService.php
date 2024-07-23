<?php

namespace App\Services\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Services\Vybe\Interfaces\VybeUnpublishRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeUnpublishRequestService
 *
 * @package App\Services\Vybe
 */
class VybeUnpublishRequestService implements VybeUnpublishRequestServiceInterface
{
    /**
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * VybeUnpublishRequestService constructor
     */
    public function __construct()
    {
        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();
    }

    /**
     * @param Collection|null $vybeUnpublishRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeUnpublishRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking vybe unpublish requests existence
             */
            if ($vybeUnpublishRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeUnpublishRequestRepository->getRequestStatusCountByIds(
                    $vybeUnpublishRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeUnpublishRequestRepository->getRequestStatusCount(
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
