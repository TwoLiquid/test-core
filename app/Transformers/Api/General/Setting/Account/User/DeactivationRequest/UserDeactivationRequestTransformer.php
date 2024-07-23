<?php

namespace App\Transformers\Api\General\Setting\Account\User\DeactivationRequest;

use App\Models\MongoDb\User\Request\Deactivation\UserDeactivationRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserDeactivationRequestTransformer
 *
 * @package App\Transformers\Api\General\Setting\Account\User\DeactivationRequest
 */
class UserDeactivationRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'account_status',
        'account_status_status',
        'previous_account_status',
        'admin',
        'request_status',
        'toast_message_type'
    ];

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return array
     */
    public function transform(UserDeactivationRequest $userDeactivationRequest) : array
    {
        return [
            'id'                 => $userDeactivationRequest->_id,
            'reason'             => $userDeactivationRequest->reason,
            'toast_message_text' => $userDeactivationRequest->toast_message_text
        ];
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeUser(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $user = $userDeactivationRequest->user;

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeAccountStatus(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $accountStatus = $userDeactivationRequest->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeAccountStatusStatus(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $accountStatusStatus = $userDeactivationRequest->getAccountStatusStatus();

        return $accountStatusStatus ? $this->item($accountStatusStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includePreviousAccountStatus(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $previousAccountStatus = $userDeactivationRequest->getPreviousAccountStatus();

        return $previousAccountStatus ? $this->item($previousAccountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $toastMessageType = $userDeactivationRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $admin = $userDeactivationRequest->admin;

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $requestStatus = $userDeactivationRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deactivation_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deactivation_requests';
    }
}
