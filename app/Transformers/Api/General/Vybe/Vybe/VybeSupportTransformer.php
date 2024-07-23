<?php

namespace App\Transformers\Api\General\Vybe\Vybe;

use App\Models\MongoDb\Vybe\VybeSupport;
use App\Transformers\BaseTransformer;

/**
 * Class VybeSupportTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Vybe
 */
class VybeSupportTransformer extends BaseTransformer
{
    /**
     * @param VybeSupport $vybeSupport
     *
     * @return array
     */
    public function transform(VybeSupport $vybeSupport) : array
    {
        return [
            'id'                     => $vybeSupport->_id,
            'category_suggestion'    => $vybeSupport->category_suggestion,
            'subcategory_suggestion' => $vybeSupport->subcategory_suggestion,
            'activity_suggestion'    => $vybeSupport->activity_suggestion,
            'device_suggestion'      => $vybeSupport->device_suggestion,
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_support';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_supports';
    }
}
