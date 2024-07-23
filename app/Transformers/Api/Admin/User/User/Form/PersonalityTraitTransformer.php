<?php

namespace App\Transformers\Api\Admin\User\User\Form;

use App\Lists\PersonalityTrait\PersonalityTraitListItem;
use App\Transformers\BaseTransformer;

/**
 * Class PersonalityTraitTransformer
 *
 * @package App\Transformers\Api\Admin\User\User\Form
 */
class PersonalityTraitTransformer extends BaseTransformer
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
