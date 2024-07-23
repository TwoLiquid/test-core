<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\UserVoiceSampleResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserVoiceSampleTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class UserVoiceSampleTransformer extends BaseTransformer
{
    /**
     * @param UserVoiceSampleResponse $userVoiceSampleResponse
     *
     * @return array
     */
    public function transform(UserVoiceSampleResponse $userVoiceSampleResponse) : array
    {
        return [
            'id'         => $userVoiceSampleResponse->id,
            'auth_id'    => $userVoiceSampleResponse->authId,
            'request_id' => $userVoiceSampleResponse->requestId,
            'url'        => $userVoiceSampleResponse->url,
            'duration'   => $userVoiceSampleResponse->duration,
            'mime'       => $userVoiceSampleResponse->mime,
            'declined'   => $userVoiceSampleResponse->declined
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_voice_sample';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_voice_samples';
    }
}
