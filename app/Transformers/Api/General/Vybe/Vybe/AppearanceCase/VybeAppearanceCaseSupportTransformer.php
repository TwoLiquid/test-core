<?php

namespace App\Transformers\Api\General\Vybe\Vybe\AppearanceCase;

use App\Models\MongoDb\Vybe\VybeAppearanceCaseSupport;
use App\Transformers\BaseTransformer;

/**
 * Class VybeAppearanceCaseSupportTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Vybe\AppearanceCase
 */
class VybeAppearanceCaseSupportTransformer extends BaseTransformer
{
    /**
     * @param VybeAppearanceCaseSupport $vybeAppearanceCaseSupport
     *
     * @return array
     */
    public function transform(VybeAppearanceCaseSupport $vybeAppearanceCaseSupport) : array
    {
        return [
            'id'              => $vybeAppearanceCaseSupport->_id,
            'unit_suggestion' => $vybeAppearanceCaseSupport->unit_suggestion
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_appearance_case_support';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_appearance_case_supports';
    }
}
