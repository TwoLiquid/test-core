<?php

namespace App\Transformers\Api\General\Setting\Account;

use App\Exceptions\DatabaseException;
use App\Models\MySql\User\User;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Transformers\BaseTransformer;
use App\Transformers\Api\General\Setting\Account\User\DeactivationRequest\UserDeactivationRequestTransformer;
use App\Transformers\Api\General\Setting\Account\User\DeletionRequest\UserDeletionRequestTransformer;
use App\Transformers\Api\General\Setting\Account\User\UnsuspendRequest\UserUnsuspendRequestTransformer;
use League\Fractal\Resource\Item;

/**
 * Class RequestTransformer
 *
 * @package App\Transformers\Api\General\Setting\Account
 */
class RequestTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * RequestTransformer constructor
     *
     * @param User $user
     */
    public function __construct(
        User $user
    )
    {
        /** @var User user */
        $this->user = $user;

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user_deactivation_request',
        'user_unsuspend_request',
        'user_deletion_request'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserDeactivationRequest() : ?Item
    {
        $deactivationRequest = null;

        if ($this->user->deactivationRequest) {
            $lastDeactivationRequest = $this->user->deactivationRequest;

            if ($lastDeactivationRequest->shown === false) {
                if ($lastDeactivationRequest->getRequestStatus()->isAccepted() ||
                    $lastDeactivationRequest->getRequestStatus()->isCanceled()
                ) {
                    $this->userDeactivationRequestRepository->updateShown(
                        $lastDeactivationRequest,
                        true
                    );
                }

                $deactivationRequest = $lastDeactivationRequest;
            }
        }

        return $deactivationRequest ? $this->item($deactivationRequest, new UserDeactivationRequestTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserUnsuspendRequest() : ?Item
    {
        $unsuspendRequest = null;

        if ($this->user->unsuspendRequest) {
            $lastUnsuspendRequest = $this->user->unsuspendRequest;

            if ($lastUnsuspendRequest->shown === false) {
                if ($lastUnsuspendRequest->getRequestStatus()->isAccepted() ||
                    $lastUnsuspendRequest->getRequestStatus()->isCanceled()
                ) {
                    $this->userUnsuspendRequestRepository->updateShown(
                        $lastUnsuspendRequest,
                        true
                    );
                }

                $unsuspendRequest = $lastUnsuspendRequest;
            }
        }

        return $unsuspendRequest ? $this->item($unsuspendRequest, new UserUnsuspendRequestTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserDeletionRequest() : ?Item
    {
        $deletionRequest = null;

        if ($this->user->deletionRequest) {
            $lastDeletionRequest = $this->user->deletionRequest;

            if ($lastDeletionRequest->shown === false) {
                if ($lastDeletionRequest->getRequestStatus()->isAccepted() ||
                    $lastDeletionRequest->getRequestStatus()->isCanceled()
                ) {
                    $this->userDeletionRequestRepository->updateShown(
                        $lastDeletionRequest,
                        true
                    );
                }

                $deletionRequest = $lastDeletionRequest;
            }
        }

        return $deletionRequest ? $this->item($deletionRequest, new UserDeletionRequestTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'requests';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'requests';
    }
}
