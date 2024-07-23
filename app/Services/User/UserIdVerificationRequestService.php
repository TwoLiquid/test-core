<?php

namespace App\Services\User;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\User\User;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Services\User\Interfaces\UserIdVerificationRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserIdVerificationRequestService
 *
 * @package App\Services\User
 */
class UserIdVerificationRequestService implements UserIdVerificationRequestServiceInterface
{
    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * UserIdVerificationRequestService constructor
     */
    public function __construct()
    {
        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();
    }

    /**
     * @param Collection|null $userIdVerificationRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $userIdVerificationRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking user id verification requests existence
             */
            if ($userIdVerificationRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->userIdVerificationRequestRepository->getRequestStatusCountByIds(
                    $userIdVerificationRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->userIdVerificationRequestRepository->getRequestStatusCount(
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
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function getLastForUser(
        User $user
    ) : ?UserIdVerificationRequest
    {
        /**
         * Getting user id verification request
         */
        $userIdVerificationRequest = $this->userIdVerificationRequestRepository->findLastForUser(
            $user
        );

        /**
         * Checking user id verification request status
         */
        if ($userIdVerificationRequest &&
            $userIdVerificationRequest->getRequestStatus()->isAccepted()
        ) {
            if ($userIdVerificationRequest->shown === true) {
                return null;
            }
        }

        return $userIdVerificationRequest;
    }
}
