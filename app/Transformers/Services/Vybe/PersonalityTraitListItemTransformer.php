<?php

namespace App\Transformers\Services\Vybe;

use App\Lists\PersonalityTrait\PersonalityTraitListItem;
use App\Transformers\BaseTransformer;

/**
 * Class PersonalityTraitListItemTransformer
 *
 * @package App\Transformers\Services\Vybe
 */
class PersonalityTraitListItemTransformer extends BaseTransformer
{
    /**
     * @param PersonalityTraitListItem $personalityTraitListItem
     *
     * @return array
     */
    public function transform(PersonalityTraitListItem $personalityTraitListItem) : array
    {
        return [
            'id'   => $personalityTraitListItem->id,
            'code' => $personalityTraitListItem->code,
            'name' => $personalityTraitListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'personality_trait';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'personality_traits';
    }
}