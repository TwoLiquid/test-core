<?php

namespace App\Transformers\Api\Admin\Vybe\UnsuspendRequest;

use App\Models\MySql\Vybe\Vybe;
use App\Transformers\BaseTransformer;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\UnsuspendRequest
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'      => $vybe->id,
            'version' => $vybe->version,
            'title'   => $vybe->title
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
