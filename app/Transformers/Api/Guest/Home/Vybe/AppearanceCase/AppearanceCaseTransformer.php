<?php

namespace App\Transformers\Api\Guest\Home\Vybe\AppearanceCase;

use App\Models\MySql\AppearanceCase;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

/**
 * Class AppearanceCaseTransformer
 *
 * @package App\Transformers\Api\Guest\Home\Vybe\AppearanceCase
 */
class AppearanceCaseTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'appearance',
        'unit',
        'city_place',
        'platforms'
    ];

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return array
     */
    public function transform(AppearanceCase $appearanceCase) : array
    {
        return [
            'id'            => $appearanceCase->id,
            'price'         => $appearanceCase->price,
            'description'   => $appearanceCase->description,
            'same_location' => $appearanceCase->same_location
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
     * @param AppearanceCase $appearanceCase
     *
     * @return Item|null
     */
    public function includeCityPlace(AppearanceCase $appearanceCase) : ?Item
    {
        $cityPlace = null;

        if ($appearanceCase->relationLoaded('cityPlace')) {
            $cityPlace = $appearanceCase->cityPlace;
        }

        return $cityPlace ? $this->item($cityPlace, new CityPlaceTransformer) : null;
    }

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return Collection|null
     */
    public function includePlatforms(AppearanceCase $appearanceCase) : ?Collection
    {
        $platforms = null;

        if ($appearanceCase->relationLoaded('platforms')) {
            $platforms = $appearanceCase->platforms;
        }

        return $platforms ? $this->collection($platforms, new PlatformTransformer) : null;
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
