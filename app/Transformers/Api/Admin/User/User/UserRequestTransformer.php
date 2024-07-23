<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Exceptions\DatabaseException;
use App\Models\MySql\User\User;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Transformers\BaseTransformer;

/**
 * Class UserRequestTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class UserRequestTransformer extends BaseTransformer
{
    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * UserRequestTransformer constructor
     */
    public function __construct()
    {
        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();
    }

    /**
     * @param User $user
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function transform(User $user) : array
    {
        return [
            'billing_change_request'       => $this->billingChangeRequestRepository->existsPendingForUser($user),
            'user_deactivation_request'    => $this->userDeactivationRequestRepository->existsPendingForUser($user),
            'user_deletion_request'        => $this->userDeletionRequestRepository->existsPendingForUser($user),
            'user_id_verification_request' => $this->userIdVerificationRequestRepository->existsPendingForUser($user),
            'user_profile_request'         => $this->userProfileRequestRepository->existsPendingForUser($user),
            'payout_method_request'        => $this->payoutMethodRequestRepository->existsPendingForUser($user),
            'user_unsuspend_request'       => $this->userUnsuspendRequestRepository->existsPendingForUser($user)
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_requests';
    }
}
