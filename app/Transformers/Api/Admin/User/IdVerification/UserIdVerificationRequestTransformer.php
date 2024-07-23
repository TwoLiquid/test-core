<?php

namespace App\Transformers\Api\Admin\User\IdVerification;

use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserIdVerificationRequestTransformer
 *
 * @package App\Transformers\Api\Admin\User\IdVerification
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
        /** @var Collection platformIcons */
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
        'user',
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

        return $userIdVerificationImage ?
            $this->item($userIdVerificationImage, new UserIdVerificationImageTransformer($userIdVerificationRequest)) :
            null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeVerificationStatus(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $idVerificationStatus = $userIdVerificationRequest->getVerificationStatus();

        return $idVerificationStatus ? $this->item($idVerificationStatus, new UserIdVerificationStatusTransformer) : null;
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
        $previousIdVerificationStatus = $userIdVerificationRequest->getPreviousVerificationStatus();

        return $previousIdVerificationStatus ? $this->item($previousIdVerificationStatus, new UserIdVerificationStatusTransformer) : null;
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
     * @param UserIdVerificationRequest $userIdVerificationRequestt
     *
     * @return Item|null
     */
    public function includeAdmin(UserIdVerificationRequest $userIdVerificationRequestt) : ?Item
    {
        $admin = null;

        if ($userIdVerificationRequestt->relationLoaded('admin')) {
            $admin = $userIdVerificationRequestt->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeUser(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $user = null;

        if ($userIdVerificationRequest->relationLoaded('user')) {
            $user = $userIdVerificationRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
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
