<?php

namespace App\Transformers\Api\Admin\Vybe\ChangeRequest;

use App\Models\MySql\Platform;
use App\Transformers\BaseTransformer;

/**
 * Class PlatformTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\ChangeRequest
 */
class PlatformTransformer extends BaseTransformer
{
    /**
     * @param Platform $platform
     *
     * @return array
     */
    public function transform(Platform $platform) : array
    {
        return [
            'id'                    => $platform->id,
            'name'                  => $platform->name,
            'code'                  => $platform->code,
            'voice_chat'            => $platform->voice_chat,
            'visible_in_voice_chat' => $platform->visible_in_voice_chat,
            'video_chat'            => $platform->video_chat,
            'visible_in_video_chat' => $platform->visible_in_video_chat
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'platform';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'platforms';
    }
}
