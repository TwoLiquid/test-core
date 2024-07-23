<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\PlatformIconResponse;
use App\Transformers\BaseTransformer;

/**
 * Class PlatformIconTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class PlatformIconTransformer extends BaseTransformer
{
    /**
     * @param PlatformIconResponse $platformIconResponse
     *
     * @return array
     */
    public function transform(PlatformIconResponse $platformIconResponse) : array
    {
        return [
            'id'          => $platformIconResponse->id,
            'platform_id' => $platformIconResponse->platformId,
            'url'         => $platformIconResponse->url,
            'mime'        => $platformIconResponse->mime
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
