<?php

namespace App\Transformers\Api\Admin\User\User\Request\Deletion;

use App\Models\MongoDb\User\Request\Deletion\UserDeletionRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserDeletionRequestTransformer
 *
 * @package App\Transformers\Api\Admin\User\User\Request\Deletion
 */
class UserDeletionRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_status',
        'account_status_status',
        'previous_account_status',
        'request_status'
    ];

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return array
     */
    public function transform(UserDeletionRequest $userDeletionRequest) : array
    {
        return [
            'id'                 => $userDeletionRequest->_id,
            'reason'             => $userDeletionRequest->reason,
            'toast_message_text' => $userDeletionRequest->toast_message_text
        ];
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeAccountStatus(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $accountStatus = $userDeletionRequest->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeAccountStatusStatus(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $accountStatusStatus = $userDeletionRequest->getAccountStatusStatus();

        return $accountStatusStatus ? $this->item($accountStatusStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includePreviousAccountStatus(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $previousAccountStatus = $userDeletionRequest->getPreviousAccountStatus();

        return $previousAccountStatus ? $this->item($previousAccountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $requestStatus = $userDeletionRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deletion_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deletion_requests';
    }
}
