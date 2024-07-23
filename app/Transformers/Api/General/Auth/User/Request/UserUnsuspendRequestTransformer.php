<?php

namespace App\Transformers\Api\General\Auth\User\Request;

use App\Models\MongoDb\User\Request\Unsuspend\UserUnsuspendRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserUnsuspendRequestTransformer
 *
 * @package App\Transformers\Api\General\Auth\User\Request
 */
class UserUnsuspendRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_status',
        'account_status_status',
        'previous_account_status',
        'request_status',
        'toast_message_type'
    ];

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return array
     */
    public function transform(UserUnsuspendRequest $userUnsuspendRequest) : array
    {
        return [
            'id'                 => $userUnsuspendRequest->_id,
            'reason'             => $userUnsuspendRequest->reason,
            'toast_message_text' => $userUnsuspendRequest->toast_message_text,
            'created_at'         => $userUnsuspendRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeAccountStatus(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $accountStatus = $userUnsuspendRequest->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeAccountStatusStatus(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $accountStatusStatus = $userUnsuspendRequest->getAccountStatusStatus();

        return $accountStatusStatus ? $this->item($accountStatusStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includePreviousAccountStatus(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $previousAccountStatus = $userUnsuspendRequest->getPreviousAccountStatus();

        return $previousAccountStatus ? $this->item($previousAccountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $toastMessageType = $userUnsuspendRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $requestStatus = $userUnsuspendRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_unsuspend_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_unsuspend_requests';
    }
}
