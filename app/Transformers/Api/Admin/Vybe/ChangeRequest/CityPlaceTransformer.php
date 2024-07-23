<?php

namespace App\Transformers\Api\Admin\Vybe\ChangeRequest;

use App\Models\MySql\Place\CityPlace;
use App\Transformers\BaseTransformer;

/**
 * Class CityPlaceTransformer
 * 
 * @package App\Transformers\Api\Admin\Vybe\ChangeRequest
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
