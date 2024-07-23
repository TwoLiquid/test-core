<?php

namespace App\Transformers\Api\Admin\Vybe\Vybe\AppearanceCase;

use App\Models\MySql\Media\PlatformIcon;
use App\Transformers\BaseTransformer;

/**
 * Class PlatformIconTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Vybe\AppearanceCase
 */
class PlatformIconTransformer extends BaseTransformer
{
    /**
     * @param PlatformIcon $platformIcon
     *
     * @return array
     */
    public function transform(PlatformIcon $platformIcon) : array
    {
        return [
            'id'   => $platformIcon->id,
            'url'  => $platformIcon->url,
            'mime' => $platformIcon->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'platform_icon';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'platform_icons';
    }
}
