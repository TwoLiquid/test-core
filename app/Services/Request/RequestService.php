<?php

namespace App\Services\Request;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Group\RequestGroupList;
use App\Lists\Request\Group\RequestGroupListItem;
use App\Lists\Request\Type\RequestTypeList;
use App\Lists\Request\Type\RequestTypeListItem;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Services\Request\Interfaces\RequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RequestService
 *
 * @package App\Services\Request
 */
class RequestService implements RequestServiceInterface
{
    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybeChangeRequestRepository
     */
    protected VybeChangeRequestRepository $vybeChangeRequestRepository;

    /**
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * RequestService constructor
     */
    public function __construct()
    {
        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybeChangeRequestRepository vybeChangeRequestRepository */
        $this->vybeChangeRequestRepository = new VybeChangeRequestRepository();

        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();

        /** @var VybeUnpublishRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();

        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();
    }

    /**
     * @param RequestGroupListItem|null $requestGroupListItem
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getRequestTypesWithCounts(
        ?RequestGroupListItem $requestGroupListItem
    ) : Collection
    {
        /**
         * Returning user request types counts
         */
        if ($requestGroupListItem->isUser()) {
            return $this->getUserRequestsCounts();
        }

        /**
         * Returning finance request types counts
         */
        if ($requestGroupListItem->isFinance()) {
            return $this->getFinanceRequestsCounts();
        }

        /**
         * Returning vybe request types counts
         */
        if ($requestGroupListItem->isVybe()) {
            return $this->getVybeRequestsCounts();
        }

        /**
         * Returning all request types counts
         */
        return $this->getUserRequestsCounts()->merge(
            $this->getFinanceRequestsCounts()->merge(
                $this->getVybeRequestsCounts()
            )
        );
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getRequestGroupsWithCounts() : Collection
    {
        /**
         * Getting a request groups
         */
        $requestGroupListItems = RequestGroupList::getItems();

        /** @var RequestGroupListItem $requestGroupListItem */
        foreach ($requestGroupListItems as $requestGroupListItem) {

            /**
             * Setting user requests total count
             */
            if ($requestGroupListItem->isUser()) {
                $requestGroupListItem->setCount(
                    $this->getUserRequestsTotalCount()
                );
            }

            /**
             * Setting finance requests total count
             */
            if ($requestGroupListItem->isFinance()) {
                $requestGroupListItem->setCount(
                    $this->getFinanceRequestsTotalCount()
                );
            }

            /**
             * Setting vybe requests total count
             */
            if ($requestGroupListItem->isVybe()) {
                $requestGroupListItem->setCount(
                    $this->getVybeRequestsTotalCount()
                );
            }
        }

        return $requestGroupListItems;
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getUserRequestsCounts() : Collection
    {
        /**
         * Getting user request types
         */
        $userRequestTypeListItems = RequestTypeList::getUserGroup();

        /** @var RequestTypeListItem $userRequestTypeListItem */
        foreach ($userRequestTypeListItems as $userRequestTypeListItem) {

            /**
             * Setting user profile requests count
             */
            if ($userRequestTypeListItem->isUserProfileRequest()) {
                $userRequestTypeListItem->setCount(
                    $this->userProfileRequestRepository->getAllCount()
                );
            }

            /**
             * Setting user id verification requests count
             */
            if ($userRequestTypeListItem->isUserIdVerificationRequest()) {
                $userRequestTypeListItem->setCount(
                    $this->userIdVerificationRequestRepository->getAllCount()
                );
            }

            /**
             * Setting user deactivation requests count
             */
            if ($userRequestTypeListItem->isUserDeactivationRequest()) {
                $userRequestTypeListItem->setCount(
                    $this->userDeactivationRequestRepository->getAllCount()
                );
            }

            /**
             * Setting user unsuspend requests count
             */
            if ($userRequestTypeListItem->isUserUnsuspendRequest()) {
                $userRequestTypeListItem->setCount(
                    $this->userUnsuspendRequestRepository->getAllCount()
                );
            }

            /**
             * Setting user deletion requests count
             */
            if ($userRequestTypeListItem->isUserDeletionRequest()) {
                $userRequestTypeListItem->setCount(
                    $this->userDeletionRequestRepository->getAllCount()
                );
            }
        }

        return $userRequestTypeListItems;
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFinanceRequestsCounts() : Collection
    {
        /**
         * Getting finance request types
         */
        $financeRequestTypeListItems = RequestTypeList::getFinanceGroup();

        /** @var RequestTypeListItem $financeRequestTypeListItem */
        foreach ($financeRequestTypeListItems as $financeRequestTypeListItem) {

            /**
             * Setting billing change requests count
             */
            if ($financeRequestTypeListItem->isBillingChangeRequest()) {
                $financeRequestTypeListItem->setCount(
                    $this->billingChangeRequestRepository->getAllCount()
                );
            }

            /**
             * Setting payout method requests count
             */
            if ($financeRequestTypeListItem->isPayoutMethodRequest()) {
                $financeRequestTypeListItem->setCount(
                    $this->payoutMethodRequestRepository->getAllCount()
                );
            }

            /**
             * Setting withdrawal requests count
             */
            if ($financeRequestTypeListItem->isWithdrawalRequest()) {
                $financeRequestTypeListItem->setCount(
                    $this->withdrawalRequestRepository->getAllCount()
                );
            }
        }

        return $financeRequestTypeListItems;
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getVybeRequestsCounts() : Collection
    {
        /**
         * Getting vybe request types
         */
        $vybeRequestTypeListItems = RequestTypeList::getVybeGroup();

        /** @var RequestTypeListItem $vybeRequestTypeListItem */
        foreach ($vybeRequestTypeListItems as $vybeRequestTypeListItem) {

            /**
             * Setting vybe publish requests count
             */
            if ($vybeRequestTypeListItem->isVybePublishRequest()) {
                $vybeRequestTypeListItem->setCount(
                    $this->vybePublishRequestRepository->getAllCount()
                );
            }

            /**
             * Setting vybe change requests count
             */
            if ($vybeRequestTypeListItem->isVybeChangeRequest()) {
                $vybeRequestTypeListItem->setCount(
                    $this->vybeChangeRequestRepository->getAllCount()
                );
            }

            /**
             * Setting vybe unpublish requests count
             */
            if ($vybeRequestTypeListItem->isVybeUnpublishRequest()) {
                $vybeRequestTypeListItem->setCount(
                    $this->vybeUnpublishRequestRepository->getAllCount()
                );
            }

            /**
             * Setting vybe unsuspend requests count
             */
            if ($vybeRequestTypeListItem->isVybeUnsuspendRequest()) {
                $vybeRequestTypeListItem->setCount(
                    $this->vybeUnsuspendRequestRepository->getAllCount()
                );
            }

            /**
             * Setting vybe publish requests count
             */
            if ($vybeRequestTypeListItem->isVybeDeletionRequest()) {
                $vybeRequestTypeListItem->setCount(
                    $this->vybeDeletionRequestRepository->getAllCount()
                );
            }
        }

        return $vybeRequestTypeListItems;
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getUserRequestsTotalCount() : int
    {
        /**
         * Returning user request types counts
         */
        return $this->userDeactivationRequestRepository->getAllCount() +
            $this->userDeletionRequestRepository->getAllCount() +
            $this->userIdVerificationRequestRepository->getAllCount() +
            $this->userProfileRequestRepository->getAllCount() +
            $this->userUnsuspendRequestRepository->getAllCount();
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getFinanceRequestsTotalCount() : int
    {
        /**
         * Returning finance request types counts
         */
        return $this->billingChangeRequestRepository->getAllCount() +
            $this->payoutMethodRequestRepository->getAllCount() +
            $this->withdrawalRequestRepository->getAllCount();
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getVybeRequestsTotalCount() : int
    {
        /**
         * Returning vybe request types counts
         */
        return $this->vybePublishRequestRepository->getAllCount() +
            $this->vybeChangeRequestRepository->getAllCount() +
            $this->vybeUnpublishRequestRepository->getAllCount() +
            $this->vybeUnsuspendRequestRepository->getAllCount() +
            $this->vybeDeletionRequestRepository->getAllCount();
    }
}
