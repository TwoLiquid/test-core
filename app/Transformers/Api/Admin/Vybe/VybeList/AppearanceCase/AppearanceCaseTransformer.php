<?php

namespace App\Transformers\Api\Admin\Vybe\VybeList\AppearanceCase;

use App\Models\MySql\AppearanceCase;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class AppearanceCaseTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\VybeList\AppearanceCase
 */
class AppearanceCaseTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'appearance',
        'unit'
    ];

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return array
     */
    public function transform(AppearanceCase $appearanceCase) : array
    {
        return [
            'id'    => $appearanceCase->id,
            'price' => $appearanceCase->price
        ];
    }

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return Item|null
     */
    public function includeAppearance(AppearanceCase $appearanceCase) : ?Item
    {
        $vybeAppearance = $appearanceCase->getAppearance();

        return $vybeAppearance ? $this->item($vybeAppearance, new VybeAppearanceTransformer) : null;
    }

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return Item|null
     */
    public function includeUnit(AppearanceCase $appearanceCase) : ?Item
    {
        $unit = null;

        if ($appearanceCase->relationLoaded('unit')) {
            $unit = $appearanceCase->unit;
        }

        return $unit ? $this->item($unit, new UnitTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'appearance_case';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'appearance_cases';
    }
}
