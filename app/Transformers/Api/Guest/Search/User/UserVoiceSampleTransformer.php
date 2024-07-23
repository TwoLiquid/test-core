<?php

namespace App\Transformers\Api\Guest\Search\User;

use App\Models\MySql\Media\UserVoiceSample;
use App\Transformers\BaseTransformer;

/**
 * Class UserAvatarTransformer
 *
 * @package App\Transformers\Api\Guest\Search\User
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
            'id'   => $userVoiceSample->id,
            'url'  => $userVoiceSample->url,
            'mime' => $userVoiceSample->mime
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
