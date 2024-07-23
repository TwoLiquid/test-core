<?php

namespace App\Transformers\Api\Admin\User\IdVerification;

use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\Media\UserIdVerificationImage;
use App\Transformers\BaseTransformer;

/**
 * Class UserIdVerificationImageTransformer
 *
 * @package App\Transformers\Api\Admin\User\IdVerification
 */
class UserIdVerificationImageTransformer extends BaseTransformer
{
    /**
     * @var UserIdVerificationRequest|null
     */
    protected ?UserIdVerificationRequest $userIdVerificationRequest;

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     */
    public function __construct(
        UserIdVerificationRequest $userIdVerificationRequest
    )
    {
        /** @var UserIdVerificationRequest userIdVerificationRequest */
        $this->userIdVerificationRequest = $userIdVerificationRequest;
    }

    /**
     * @param UserIdVerificationImage $userIdVerificationImage
     *
     * @return array
     */
    public function transform(UserIdVerificationImage $userIdVerificationImage) : array
    {
        return [
            'id'         => $userIdVerificationImage->id,
            'url'        => $userIdVerificationImage->url,
            'url_min'    => $userIdVerificationImage->url_min,
            'mime'       => $userIdVerificationImage->mime,
            'created_at' => $this->userIdVerificationRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verification_images';
    }
}
