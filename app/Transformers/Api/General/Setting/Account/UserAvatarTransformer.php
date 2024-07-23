<?php

namespace App\Transformers\Api\General\Setting\Account;

use App\Models\MySql\Media\UserAvatar;
use App\Transformers\BaseTransformer;

/**
 * Class UserAvatarTransformer
 *
 * @package App\Transformers\Api\General\Setting\Account
 */
class UserAvatarTransformer extends BaseTransformer
{
    /**
     * @param UserAvatar $userAvatar
     *
     * @return array
     */
    public function transform(UserAvatar $userAvatar) : array
    {
        return [
            'id'       => $userAvatar->id,
            'url'      => $userAvatar->url,
            'url_min'  => $userAvatar->url_min,
            'mime'     => $userAvatar->mime,
            'declined' => $userAvatar->declined
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_avatar';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_avatars';
    }
}
