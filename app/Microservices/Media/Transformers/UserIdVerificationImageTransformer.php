<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\UserIdVerificationImageResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserIdVerificationImageTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class UserIdVerificationImageTransformer extends BaseTransformer
{
    /**
     * @param UserIdVerificationImageResponse $userIdVerificationImageResponse
     *
     * @return array
     */
    public function transform(UserIdVerificationImageResponse $userIdVerificationImageResponse) : array
    {
        return [
            'id'         => $userIdVerificationImageResponse->id,
            'auth_id'    => $userIdVerificationImageResponse->authId,
            'request_id' => $userIdVerificationImageResponse->requestId,
            'url'        => $userIdVerificationImageResponse->url,
            'url_min'    => $userIdVerificationImageResponse->urlMin,
            'mime'       => $userIdVerificationImageResponse->mime,
            'declined'   => $userIdVerificationImageResponse->declined
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
