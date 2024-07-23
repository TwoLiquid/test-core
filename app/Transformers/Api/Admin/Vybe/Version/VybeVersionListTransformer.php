<?php

namespace App\Transformers\Api\Admin\Vybe\Version;

use App\Models\MongoDb\Vybe\VybeVersion;
use App\Transformers\BaseTransformer;

/**
 * Class VybeVersionListTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Version
 */
class VybeVersionListTransformer extends BaseTransformer
{
    /**
     * @param VybeVersion $vybeVersion
     *
     * @return array
     */
    public function transform(VybeVersion $vybeVersion) : array
    {
        return [
            'id'         => $vybeVersion->_id,
            'vybe_id'    => $vybeVersion->vybe_id,
            'type'       => $vybeVersion->type,
            'created_at' => $vybeVersion->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_version';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_versions';
    }
}
