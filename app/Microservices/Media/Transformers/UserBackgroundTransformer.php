<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\UserBackgroundResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserBackgroundTransformer
 *
 * @package App\Transformers\Api
 */
class UserBackgroundTransformer extends BaseTransformer
{
    /**
     * @param UserBackgroundResponse $userBackground
     *
     * @return array
     */
    public function transform(UserBackgroundResponse $userBackground) : array
    {
        return [
            'id'         => $userBackground->id,
            'auth_id'    => $userBackground->authId,
            'request_id' => $userBackground->requestId,
            'url'        => $userBackground->url,
            'url_min'    => $userBackground->urlMin,
            'mime'       => $userBackground->mime,
            'declined'   => $userBackground->declined
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_background';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_backgrounds';
    }
}
