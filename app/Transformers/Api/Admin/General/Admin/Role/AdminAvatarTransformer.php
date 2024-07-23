<?php

namespace App\Transformers\Api\Admin\General\Admin\Role;

use App\Models\MySql\Media\AdminAvatar;
use App\Transformers\BaseTransformer;

/**
 * Class AdminAvatarTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin\Role
 */
class AdminAvatarTransformer extends BaseTransformer
{
    /**
     * @param AdminAvatar $adminAvatar
     *
     * @return array
     */
    public function transform(AdminAvatar $adminAvatar) : array
    {
        return [
            'id'      => $adminAvatar->id,
            'url'     => $adminAvatar->url,
            'url_min' => $adminAvatar->url_min,
            'mime'    => $adminAvatar->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'admin_avatar';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'admin_avatars';
    }
}
