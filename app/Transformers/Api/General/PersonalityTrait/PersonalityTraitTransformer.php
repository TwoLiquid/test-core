<?php

namespace App\Transformers\Api\General\PersonalityTrait;

use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class PersonalityTraitTransformer
 *
 * @package App\Transformers\Api\General\PersonalityTrait
 */
class PersonalityTraitTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'trait'
    ];

    /**
     * @param PersonalityTrait $personalityTrait
     *
     * @return array
     */
    public function transform(PersonalityTrait $personalityTrait) : array
    {
        return [
            'votes' => $personalityTrait->votes,
            'voted' => count($personalityTrait->voters) > 0
        ];
    }

    /**
     * @param PersonalityTrait $personalityTrait
     *
     * @return Item|null
     */
    public function includeTrait(PersonalityTrait $personalityTrait) : ?Item
    {
        $personalityTraitListItem = $personalityTrait->getTrait();

        return $personalityTraitListItem ? $this->item($personalityTraitListItem, new PersonalityTraitListItemTransformer) : null;
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
