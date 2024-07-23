<?php

namespace App\Transformers\Api\General\Dashboard\Profile;

use App\Models\MySql\Media\UserVoiceSample;
use App\Transformers\BaseTransformer;

/**
 * Class UserVoiceSampleTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile
 */
class UserVoiceSampleTransformer extends BaseTransformer
{
    /**
     * @param UserVoiceSample $userVoiceSample
     *
     * @return array
     */
    public function transform(UserVoiceSample $userVoiceSample) : array
    {
        return [
            'id'       => $userVoiceSample->id,
            'url'      => $userVoiceSample->url,
            'mime'     => $userVoiceSample->mime,
            'declined' => $userVoiceSample->declined
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
