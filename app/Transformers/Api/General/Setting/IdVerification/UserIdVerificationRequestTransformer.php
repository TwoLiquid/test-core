<?php

namespace App\Transformers\Api\General\Setting\IdVerification;

use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserIdVerificationRequestTransformer
 *
 * @package App\Transformers\Api\General\Setting\IdVerification
 */
class UserIdVerificationRequestTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $userIdVerificationImages;

    /**
     * UserIdVerificationRequestTransformer constructor
     *
     * @param Collection|null $userIdVerificationImages
     */
    public function __construct(
        Collection $userIdVerificationImages = null
    )
    {
        /** @var Collection userIdVerificationImages */
        $this->userIdVerificationImages = $userIdVerificationImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'image',
        'verification_status',
        'verification_status_status',
        'previous_verification_status',
        'admin',
        'request_status',
        'toast_message_type'
    ];

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return array
     */
    public function transform(UserIdVerificationRequest $userIdVerificationRequest) : array
    {
        return [
            'id'                 => $userIdVerificationRequest->_id,
            'user_id'            => $userIdVerificationRequest->user_id,
            'shown'              => $userIdVerificationRequest->shown,
            'toast_message_text' => $userIdVerificationRequest->toast_message_text
        ];
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeImage(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $userIdVerificationImage = $this->userIdVerificationImages?->filter(function ($item) use ($userIdVerificationRequest) {
            return $item->request_id == $userIdVerificationRequest->_id;
        })->first();

        return $userIdVerificationImage ? $this->item($userIdVerificationImage, new UserIdVerificationImageTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeVerificationStatus(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $userIdVerificationStatus = $userIdVerificationRequest->getVerificationStatus();

        return $userIdVerificationStatus ? $this->item($userIdVerificationStatus, new UserIdVerificationStatusTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeVerificationStatusStatus(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $userIdVerificationStatusStatus = $userIdVerificationRequest->getVerificationStatusStatus();

        return $userIdVerificationStatusStatus ? $this->item($userIdVerificationStatusStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includePreviousVerificationStatus(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $previousUserIdVerificationStatus = $userIdVerificationRequest->getPreviousVerificationStatus();

        return $previousUserIdVerificationStatus ? $this->item($previousUserIdVerificationStatus, new UserIdVerificationStatusTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $toastMessageType = $userIdVerificationRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $admin = $userIdVerificationRequest->admin;

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $requestStatus = $userIdVerificationRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verification_requests';
    }
}
