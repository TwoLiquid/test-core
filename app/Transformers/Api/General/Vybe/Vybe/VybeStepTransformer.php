<?php

namespace App\Transformers\Api\General\Vybe\Vybe;

use App\Lists\Vybe\Step\VybeStepListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeStepTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Vybe
 */
class VybeStepTransformer extends BaseTransformer
{
    /**
     * @param VybeStepListItem $vybeStepListItem
     *
     * @return array
     */
    public function transform(VybeStepListItem $vybeStepListItem) : array
    {
        return [
            'id'   => $vybeStepListItem->id,
            'code' => $vybeStepListItem->code,
            'name' => $vybeStepListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_step';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_steps';
    }
}
