<?php

namespace App\Transformers\Api\General\Vybe\PublishRequest;

use App\Models\MySql\Platform;
use App\Transformers\BaseTransformer;

/**
 * Class PlatformTransformer
 *
 * @package App\Transformers\Api\General\Vybe\PublishRequest
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
            'id'   => $platform->id,
            'code' => $platform->code,
            'name' => $platform->name
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
