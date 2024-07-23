<?php

namespace App\Transformers\Api\Guest\Profile\Home\Vybe\AppearanceCase;

use App\Models\MySql\Place\CityPlace;
use App\Transformers\BaseTransformer;

/**
 * Class CityPlaceTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home\Vybe\AppearanceCase
 */
class CityPlaceTransformer extends BaseTransformer
{
    /**
     * @param CityPlace $cityPlace
     *
     * @return array
     */
    public function transform(CityPlace $cityPlace) : array
    {
        return [
            'id'       => $cityPlace->id,
            'place_id' => $cityPlace->place_id,
            'name'     => $cityPlace->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'city_place';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'city_places';
    }
}